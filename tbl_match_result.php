<?php include 'dbconnection.php'; ?>
<?php include 'header.php'; ?>

<main id="main" class="main">
    <div class="pagetitle"></div>
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $season_id = $_POST['season_id'];
    $series_id = $_POST['series_id'];
    $team_1 = $_POST['team_1'];
    $team_2 = $_POST['team_2'];
    $toss_winner = $_POST['toss_winner'];
    $batting_team = $_POST['batting_team'];
    $match_result = $_POST['match_result'];
    $winner_team = $_POST['winner_team'] ?? NULL;
    $loser_team = $_POST['loser_team'] ?? NULL;

    $toss_loser = ($toss_winner == $team_1) ? $team_2 : $team_1;
    $fielding_team = ($batting_team == $team_1) ? $team_2 : $team_1;

    // Insert data into matches table
    $insert = $conn->prepare(
        "INSERT INTO tbl_matches (season_id, series_id, team_1, team_2, toss_winner, toss_loser, batting_team, fielding_team, match_result, winner_team, loser_team) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $insert->bind_param(
        "iiiiiiissii", 
        $season_id, $series_id, $team_1, $team_2, $toss_winner, $toss_loser, 
        $batting_team, $fielding_team, $match_result, $winner_team, $loser_team
    );

    if ($insert->execute()) {
        echo "Match details saved successfully!";
    } else {
        echo "Error: " . $insert->error;
    }

    $insert->close();
}




            $conn->close();
            ?>
            <html lang="en">
            <head>
                <title>Toss and Match Result Entry</title>
                <script>
                    function updateTeam2Options() {
                        var team1 = document.getElementById("team_1").value;
                        var team2Options = document.querySelectorAll("#team_2 option");

                        team2Options.forEach(option => {
                            option.style.display = (option.value === team1) ? "none" : "block";
                        });
                    }

                    function updateTossSelection() {
                        var tossWinner = document.querySelector('input[name="toss_winner"]:checked');
                        if (tossWinner) {
                            var tossWinnerValue = tossWinner.value;
                            var team1 = document.getElementById("team_1").value;
                            var team2 = document.getElementById("team_2").value;

                            // Automatically update toss loser
                            document.getElementById("team_1_loss").checked = (tossWinnerValue !== team1);
                            document.getElementById("team_2_loss").checked = (tossWinnerValue !== team2);
                        }
                    }

                    function updateBattingSelection() {
                        var battingTeam = document.querySelector('input[name="batting_team"]:checked');
                        if (battingTeam) {
                            var battingTeamValue = battingTeam.value;
                            var team1 = document.getElementById("team_1").value;
                            var team2 = document.getElementById("team_2").value;

                            // Automatically update fielding team
                            document.getElementById("team_1_fielding").checked = (battingTeamValue !== team1);
                            document.getElementById("team_2_fielding").checked = (battingTeamValue !== team2);
                        }
                    }
                    function showWinnerLoser() {
    var matchResult = document.querySelector('input[name="match_result"]:checked').value;
    var winnerLoserDiv = document.getElementById("winner_loser");

    if (matchResult === "Win" || matchResult === "Loss") {
        winnerLoserDiv.style.display = "block";
    } else {
        winnerLoserDiv.style.display = "none";
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
    <select id="team_1" name="team_1" class="form-control" required onchange="updateTeam2Options()">
        <?php while ($row = $teams->fetch_assoc()) { ?>
            <option value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
        <?php } ?>
    </select><br>

    <label for="team_2">Team 2:</label>
    <select id="team_2" name="team_2" class="form-control" required>
        <?php $teams->data_seek(0); while ($row = $teams->fetch_assoc()) { ?>
            <option value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
        <?php } ?>
    </select><br>

    <label>Toss Winner:</label><br>
    <?php $teams->data_seek(0); while ($row = $teams->fetch_assoc()) { ?>
        <input type="radio" name="toss_winner" value="<?= $row['id']; ?>" onchange="updateTossSelection()"> <?= $row['title']; ?><br>
    <?php } ?>

    <label>Batting Team:</label><br>
    <?php $teams->data_seek(0); while ($row = $teams->fetch_assoc()) { ?>
        <input type="radio" name="batting_team" value="<?= $row['id']; ?>" onchange="updateBattingSelection()"> <?= $row['title']; ?><br>
    <?php } ?>

    <label>Match Result:</label><br>
    <input type="radio" name="match_result" value="Win" required onchange="showWinnerLoser()"> Win<br>
    <input type="radio" name="match_result" value="Loss" required onchange="showWinnerLoser()"> Loss<br>
    <input type="radio" name="match_result" value="Draw" required onchange="showWinnerLoser()"> Draw<br>
    <input type="radio" name="match_result" value="Abandon" required onchange="showWinnerLoser()"> Abandon<br>
    <input type="radio" name="match_result" value="Tie" required onchange="showWinnerLoser()"> Tie<br>

    <div id="winner_loser" style="display:none;">
        <label for="winner_team">Winner Team:</label>
        <select id="winner_team" name="winner_team" class="form-control">
            <option value="">-- Select Winner Team --</option>
            <?php $teams->data_seek(0); while ($row = $teams->fetch_assoc()) { ?>
                <option value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
            <?php } ?>
        </select><br>

        <label for="loser_team">Loser Team:</label>
        <select id="loser_team" name="loser_team" class="form-control">
            <option value="">-- Select Loser Team --</option>
            <?php $teams->data_seek(0); while ($row = $teams->fetch_assoc()) { ?>
                <option value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
            <?php } ?>
        </select><br>
    </div>

    <button type="submit" class="btn btn-success my-3">Save Match</button>
</form>

            </body>
            </html>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
