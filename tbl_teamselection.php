<?php
include 'dbconnection.php';
include 'header.php';

$selected_team_1_players = [];
$selected_team_2_players = [];

// Handle form submission
if (isset($_POST['submit'])) {
    $series_id = mysqli_real_escape_string($con, $_POST['series_id']);
    $season_id = mysqli_real_escape_string($con, $_POST['season_id']);
    $team_1 = mysqli_real_escape_string($con, $_POST['team_1']);
    $team_2 = mysqli_real_escape_string($con, $_POST['team_2']);
    $match_no = mysqli_real_escape_string($con, $_POST['match_no']);
    
    // Prepare and execute statement for Team 1 Players
    if (isset($_POST['team_1_players']) && !empty($_POST['team_1_players'])) {
        $team_1_players = $_POST['team_1_players'];
        $stmt = $con->prepare("INSERT INTO tbl_teamselection (series_id, season_id, team_1, team_2, match_no, player_team1) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($team_1_players as $player_team1) {
            $stmt->bind_param("iissss", $series_id, $season_id, $team_1, $team_2, $match_no, $player_team1);
            if (!$stmt->execute()) {
                echo '<script>alert("Error inserting Team 1 player: ' . $stmt->error . '");</script>';
            }
        }
        $stmt->close();
    }

    // Prepare and execute statement for Team 2 Players
    if (isset($_POST['team_2_players']) && !empty($_POST['team_2_players'])) {
        $team_2_players = $_POST['team_2_players'];
        $stmt = $con->prepare("INSERT INTO tbl_teamselection (series_id, season_id, team_1, team_2, match_no, player_team2) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($team_2_players as $player_team2) {
            $stmt->bind_param("iissss", $series_id, $season_id, $team_1, $team_2, $match_no, $player_team2);
            if (!$stmt->execute()) {
                echo '<script>alert("Error inserting Team 2 player: ' . $stmt->error . '");</script>';
            }
        }
        $stmt->close();
    }

    echo '<script>alert("Players inserted successfully.");</script>';
}

// Fetch already selected players
$query = "SELECT player_team1, player_team2 FROM tbl_teamselection WHERE series_id = ? AND season_id = ? AND team_1 = ? AND team_2 = ? AND match_no = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("iiiss", $_POST['series_id'], $_POST['season_id'], $_POST['team_1'], $_POST['team_2'], $_POST['match_no']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    if (!empty($row['player_team1'])) {
        $selected_team_1_players[] = $row['player_team1'];
    }
    if (!empty($row['player_team2'])) {
        $selected_team_2_players[] = $row['player_team2'];
    }
}
$stmt->close();
?>

<section class="section dashboard">
  <div class="row">
    <div class="container my-3">
      <form action="" method="POST" id="check" autocomplete="off" enctype="multipart/form-data">
        <div class="container">
            <h2 class="text-center">Selection of Players in Teams</h2>

            <!-- Series, Season, and Match Selection -->
            <div class="row mb-3">
                <!-- Series Selection -->
                <div class="col-sm-12">
                    <select class="col-md-12 mt-3 mb-3 form-control" aria-label="Default select example" name="series_id">
                        <option selected>Series Name</option>
                        <?php
                        $title = mysqli_query($con, "SELECT * FROM tbl_series");
                        while ($t = mysqli_fetch_assoc($title)) {
                            echo '<option value="' . $t['id'] . '">' . $t['title'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <!-- Season Selection -->
                <div class="col-md-12">
                    <select class="col-md-8 form-control" aria-label="Default select example" name="season_id">
                        <option selected>Season</option>
                        <?php
                        $title = mysqli_query($con, "SELECT * FROM tbl_season");
                        while ($t = mysqli_fetch_assoc($title)) {
                            echo '<option value="' . $t['id'] . '">' . $t['title'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <!-- Team 1 and Team 2 Selection -->
                <div class="col-sm-6">
                    <select class="col-md-12 mt-3 mb-3 form-control" aria-label="Default select example" name="team_1">
                        <option selected>Team 1</option>
                        <?php
                        $title = mysqli_query($con, "SELECT * FROM tbl_team");
                        while ($m = mysqli_fetch_assoc($title)) {
                            echo '<option value="' . $m['id'] . '">' . $m['title'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <select class="col-md-12 mt-3 mb-3 form-control" aria-label="Default select example" name="team_2">
                        <option selected>Team 2</option>
                        <?php
                        $title = mysqli_query($con, "SELECT * FROM tbl_team");
                        while ($m = mysqli_fetch_assoc($title)) {
                            echo '<option value="' . $m['id'] . '">' . $m['title'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <!-- Match Number Selection -->
                <div class="col-sm-12">
                    <select class="col-md-12 mt-3 mb-3 form-control" aria-label="Default select example" name="match_no">
                        <option selected>Match Number</option>
                        <?php
                        $match_no = mysqli_query($con, "SELECT * FROM tbl_schedule");
                        while ($m = mysqli_fetch_assoc($match_no)) {
                            echo '<option value="' . $m['match_no'] . '">' . $m['match_no'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Player Selection for Team 1 -->
            <h4>Players for Team 1</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>ID</th>
                        <th>Player Name</th>
                    </tr>
                </thead>
                <tbody id="team1-players">
                    <?php
                    $sql = "SELECT * FROM tbl_player";
                    $result = mysqli_query($con, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $player_name = $row["name"];
                        $player_id = $row["id"];
                        $disabled = in_array($player_id, $selected_team_1_players) || in_array($player_id, $selected_team_2_players) ? 'disabled' : '';
                        echo '<tr>
                                <td><input class="form-check-input" type="checkbox" name="team_1_players[]" value="' . $player_id . '" data-name="' . $player_name . '" ' . $disabled . '></td>
                                <td>' . $player_id . '</td>
                                <td>' . $player_name . '</td>
                            </tr>';
                    }
                    ?>
                </tbody>
            </table>

            <!-- Player Selection for Team 2 -->
            <h4>Players for Team 2</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>ID</th>
                        <th>Player Name</th>
                    </tr>
                </thead>
                <tbody id="team2-players">
                    <?php
                    $sql = "SELECT * FROM tbl_player";
                    $result = mysqli_query($con, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $player_name = $row["name"];
                        $player_id = $row["id"];
                        $disabled = in_array($player_id, $selected_team_1_players) || in_array($player_id, $selected_team_2_players) ? 'disabled' : '';
                        echo '<tr>
                                <td><input class="form-check-input" type="checkbox" name="team_2_players[]" value="' . $player_id . '" data-name="' . $player_name . '" ' . $disabled . '></td>
                                <td>' . $player_id . '</td>
                                <td>' . $player_name . '</td>
                            </tr>';
                    }
                    ?>
                </tbody>
            </table>

            <button type="submit" name="submit" class="btn btn-primary col-md-2 mt-4">Submit</button>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to update player visibility
    function updatePlayerVisibility() {
        let selectedTeam1Players = Array.from(document.querySelectorAll('input[name="team_1_players[]"]:checked')).map(checkbox => checkbox.dataset.name);
        let selectedTeam2Players = Array.from(document.querySelectorAll('input[name="team_2_players[]"]:checked')).map(checkbox => checkbox.dataset.name);

        // Hide players in Team 2 that are selected in Team 1
        document.querySelectorAll('#team2-players input').forEach(checkbox => {
            checkbox.closest('tr').style.display = selectedTeam1Players.includes(checkbox.dataset.name) ? 'none' : '';
        });

        // Hide players in Team 1 that are selected in Team 2
        document.querySelectorAll('#team1-players input').forEach(checkbox => {
            checkbox.closest('tr').style.display = selectedTeam2Players.includes(checkbox.dataset.name) ? 'none' : '';
        });
    }

    // Initial update
    updatePlayerVisibility();

    // Attach change event listeners to checkboxes
    document.querySelectorAll('input[name="team_1_players[]"], input[name="team_2_players[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updatePlayerVisibility);
    });
});
</script>

<?php
include 'footer.php';
?>
</body>
</html>
