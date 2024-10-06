<?php include 'header.php'; ?>
<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "cric_stats");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch batsman dropdown options
$battsman_query = "SELECT DISTINCT name FROM tbl_player";
$battsman_result = $conn->query($battsman_query);
if (!$battsman_result) {
    die("Error fetching batsman data: " . $conn->error);
}

// Fetch match dropdown options
$match_query = "SELECT id, season_id, series_id, team_1, team_2 FROM tbl_matches";
$match_result = $conn->query($match_query);
if (!$match_result) {
    die("Error fetching match data: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $battsman = $conn->real_escape_string($_POST['battsman']);
    $match_id = (int)$_POST['match_id'];

    // Fetch match details
    $match_details_query = "SELECT * FROM tbl_matches WHERE id = $match_id";
    $match_details_result = $conn->query($match_details_query);

    if ($match_details_result && $match_details_result->num_rows > 0) {
        $match_details = $match_details_result->fetch_assoc();

        $team_1 = $match_details['team_1'];
        $team_2 = $match_details['team_2'];
        $batting_team = $match_details['batting_team'];
        $fielding_team = $match_details['fielding_team'];

        // Check which team the batsman belongs to
        $battsman_team_query = "SELECT team_1 FROM tbl_team_record WHERE battsman = '$battsman' AND match_id = $match_id";
        $battsman_team_result = $conn->query($battsman_team_query);

        if ($battsman_team_result && $battsman_team_result->num_rows > 0) {
            $battsman_team_row = $battsman_team_result->fetch_assoc();
            $battsman_team = $battsman_team_row['team_1'];

            // Check if batsman's team batted or fielded first
            if ($battsman_team == $batting_team) {
                // Batsman's team batted first
                echo "<h4>Batsman batted first. Fetching batting performance...</h4>";

                // Fetch batting performance data
                $batting_performance_query = "SELECT dot_ball, one_run, two_run, three_run, four_run, six_run 
                                              FROM tbl_team_record 
                                              WHERE battsman = '$battsman' AND match_id = $match_id";
                $batting_performance_result = $conn->query($batting_performance_query);
                
                if ($batting_performance_result && $batting_performance_result->num_rows > 0) {
                    $batting_performance = $batting_performance_result->fetch_assoc();

                    // Calculate total runs
                    $total_runs = $batting_performance['one_run'] +
                                  2 * $batting_performance['two_run'] +
                                  3 * $batting_performance['three_run'] +
                                  4 * $batting_performance['four_run'] +
                                  6 * $batting_performance['six_run'];

                    echo "<p>Total Runs Scored: " . $total_runs . "</p>";
                } else {
                    echo "<p>No batting performance data found for this batsman.</p>";
                }

            } elseif ($battsman_team == $fielding_team) {
                // Batsman's team fielded first
                echo "<h4>Batsman fielded first. Fetching fielding performance...</h4>";

                // Fetch fielding performance data
                $fielding_performance_query = "SELECT dot_ball, one_run, two_run, three_run, four_run, six_run 
                                               FROM tbl_team_record 
                                               WHERE battsman = '$battsman' AND match_id = $match_id";
                $fielding_performance_result = $conn->query($fielding_performance_query);

                if ($fielding_performance_result && $fielding_performance_result->num_rows > 0) {
                    $fielding_performance = $fielding_performance_result->fetch_assoc();

                    // Calculate total runs given during fielding
                    $total_fielding_runs = $fielding_performance['one_run'] +
                                           2 * $fielding_performance['two_run'] +
                                           3 * $fielding_performance['three_run'] +
                                           4 * $fielding_performance['four_run'] +
                                           6 * $fielding_performance['six_run'];

                    echo "<p>Total Runs Given during Fielding: " . $total_fielding_runs . "</p>";
                } else {
                    echo "<p>No fielding performance data found for this batsman.</p>";
                }

            } else {
                echo "<p>Batsman doesn't belong to either team.</p>";
            }
        } else {
            echo "<p>Batsman not found in this match.</p>";
        }
    } else {
        echo "<p>Match not found.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Batsman Performance</title>
</head>
<body>

<h3 class="text-center">Select Batsman and Match</h3>
<form method="post">
    <label for="battsman" class="my-2">Batsman:</label>
    <select name="battsman" id="battsman" class="form-control">
        <?php
        while ($row = $battsman_result->fetch_assoc()) {
            echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
        }
        ?>
    </select>
    <br><br>

    <label for="match_id" class="my-2">Match:</label>
    <select name="match_id" id="match_id" class="form-control">
        <?php
        while ($row = $match_result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>Match ID: " . $row['id'] . "</option>";
        }
        ?>
    </select>
    <br><br>

    <input type="submit" class="btn btn-success" value="Get Performance">
</form>

</body>
</html>
<?php include 'footer.php'; ?>