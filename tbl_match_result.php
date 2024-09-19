<?php include 'dbconnection.php' ?>

<?php include 'header.php' ?>

<main id="main" class="main">

    <div class="pagetitle">
     
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "cric_stats");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch dropdown options from database
$seasons = $conn->query("SELECT id, title FROM tbl_season");
$series = $conn->query("SELECT id, title FROM tbl_series");
$teams = $conn->query("SELECT id, title FROM tbl_team");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $season_id = $_POST['season_id'];
    $series_id = $_POST['series_id'];
    $team_1 = $_POST['team_1'];
    $team_2 = $_POST['team_2'];
    $toss_winner = $_POST['toss_winner'];
    $batting_team = $_POST['batting_team'];

    // Determine the toss loser and fielding team
    $toss_loser = ($toss_winner == $team_1) ? $team_2 : $team_1;
    $fielding_team = ($batting_team == $team_1) ? $team_2 : $team_1;

    // Insert data into matches table
    $insert = $conn->prepare("INSERT INTO tbl_matches (season_id, series_id, team_1, team_2, toss_winner, toss_loser, batting_team, fielding_team) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("iiiiiiii", $season_id, $series_id, $team_1, $team_2, $toss_winner, $toss_loser, $batting_team, $fielding_team);

    if ($insert->execute()) {
        echo "Match details saved successfully!";
    } else {
        echo "Error: " . $insert->error;
    }

    $insert->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toss and Batting Selection</title>
    <script>
        function updateTossSelection() {
            var tossWinner = document.querySelector('input[name="toss_winner"]:checked');
            if (tossWinner) {
                var tossWinnerValue = tossWinner.value;
                var team1 = document.getElementById("team_1").value;
                var team2 = document.getElementById("team_2").value;

                // Automatically update toss loser
                if (tossWinnerValue === team1) {
                    document.getElementById("team_2_loss").checked = true;
                    document.getElementById("team_1_loss").checked = false;
                } else {
                    document.getElementById("team_1_loss").checked = true;
                    document.getElementById("team_2_loss").checked = false;
                }
            }
        }

        function updateBattingSelection() {
            var battingTeam = document.querySelector('input[name="batting_team"]:checked');
            if (battingTeam) {
                var battingTeamValue = battingTeam.value;
                var team1 = document.getElementById("team_1").value;
                var team2 = document.getElementById("team_2").value;

                // Automatically update fielding team
                if (battingTeamValue === team1) {
                    document.getElementById("team_2_fielding").checked = true;
                    document.getElementById("team_1_fielding").checked = false;
                } else {
                    document.getElementById("team_1_fielding").checked = true;
                    document.getElementById("team_2_fielding").checked = false;
                }
            }
        }
    </script>
</head>
<body>

<form method="POST">
    <label for="season_id">Season ID:</label>
    <select id="season_id" name="season_id" class="form-control" required>
        <?php while ($row = $seasons->fetch_assoc()) { ?>
            <option value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
        <?php } ?>
    </select><br>

    <label for="series_id">Series ID:</label>
    <select id="series_id" name="series_id" class="form-control" required>
        <?php while ($row = $series->fetch_assoc()) { ?>
            <option value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
        <?php } ?>
    </select><br>

    <label for="team_1">Team 1:</label>
    <select id="team_1" name="team_1" class="form-control" required>
        <?php
        $teams->data_seek(0); // Reset the pointer to the beginning
        while ($row = $teams->fetch_assoc()) { ?>
            <option value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
        <?php } ?>
    </select><br>

    <label for="team_2">Team 2:</label>
    <select id="team_2" name="team_2" class="form-control" required>
        <?php
        $teams->data_seek(0); // Reset the pointer to the beginning
        while ($row = $teams->fetch_assoc()) { ?>
            <option value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
        <?php } ?>
    </select><br>

    <label>Toss Winner:</label><br>
    <?php
    $teams->data_seek(0); // Reset the pointer to the beginning
    while ($row = $teams->fetch_assoc()) { ?>
        <input type="radio" name="toss_winner" value="<?= $row['id']; ?>" id="team_<?= $row['id']; ?>" onchange="updateTossSelection()"> <?= $row['title']; ?><br>
    <?php } ?>

    <label>Toss Loser:</label><br>
    <input type="radio" id="team_1_loss" name="toss_loser" class="my-2" disabled> Team 1 Loss<br>
    <input type="radio" id="team_2_loss" name="toss_loser" disabled> Team 2 Loss<br>

    <label>Batting Selection:</label><br>
    <?php
    $teams->data_seek(0); // Reset the pointer to the beginning
    while ($row = $teams->fetch_assoc()) { ?>
        <input type="radio" name="batting_team" value="<?= $row['id']; ?>" id="batting_team_<?= $row['id']; ?>" onchange="updateBattingSelection()"> <?= $row['title']; ?><br>
    <?php } ?>

    <label>Fielding Selection:</label><br>
    <input type="radio" id="team_1_fielding" name="fielding_team" class="my-2" disabled> Team 1 Fielding<br>
    <input type="radio" id="team_2_fielding" name="fielding_team" disabled> Team 2 Fielding<br>

    <button type="submit" class="btn btn-success my-3">Save Match</button>
</form>

</body>
</html>
</div>
    </section>

  </main><!-- End #main -->
</body>

<?php include 'footer.php' ?> 