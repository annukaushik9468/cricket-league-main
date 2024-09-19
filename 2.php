<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "cric_stats");

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data for dropdowns from tbl_schedule
function fetchScheduleOptions($conn, $column) {
    $options = "";
    $query = "SELECT DISTINCT $column FROM tbl_schedule";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $options .= "<option value='" . $row[$column] . "'>" . $row[$column] . "</option>";
        }
    } else {
        echo "Error fetching options: " . $conn->error;
    }
    return $options;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $season_id = isset($_POST['season_id']) ? $_POST['season_id'] : null;
    $series_id = isset($_POST['series_id']) ? $_POST['series_id'] : null;
    $match_no = isset($_POST['match_no']) ? $_POST['match_no'] : null;
    $team_1 = isset($_POST['team_1']) ? $_POST['team_1'] : null;
    $team_2 = isset($_POST['team_2']) ? $_POST['team_2'] : null;

    // Validate if required fields are selected
    if ($season_id && $series_id && $match_no && $team_1 && $team_2) {
        // Fetch batting_team and toss_winner from tbl_matches
        $query = "SELECT batting_team, toss_winner FROM tbl_matches WHERE team_1 = ? AND team_2 = ?";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("ii", $team_1, $team_2);
            $stmt->execute();
            $stmt->bind_result($batting_team, $toss_winner);
            $stmt->fetch();
            $stmt->close();

            if ($batting_team && $toss_winner) {
                // Fetch player_name and upload_path from tbl_teamplayer and insert data
                $insert_query = "INSERT INTO tbl_match_data (season_id, series_id, match_no, team_1, team_2, batting_team, toss_winner, player_name, upload_path)
                                 SELECT ?, ?, ?, ?, ?, ?, ?, player_name, upload_path FROM tbl_match_data WHERE team_1 = ?";
                $insert_stmt = $conn->prepare($insert_query);
                
                if ($insert_stmt) {
                    $insert_stmt->bind_param("iiiiiiii", $season_id, $series_id, $match_no, $team_1, $team_2, $batting_team, $toss_winner, $batting_team);
                    $insert_stmt->execute();
                    $insert_stmt->close();

                    echo "Data inserted successfully!";
                } else {
                    echo "Error in insert query: " . $conn->error;
                }
            } else {
                echo "No data found for the selected match.";
            }
        } else {
            echo "Error in fetch query: " . $conn->error;
        }
    } else {
        echo "Please fill all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert Team Player</title>
</head>
<body>
    <form method="post">
        <label for="season_id">Season ID:</label>
        <select name="season_id" id="season_id" required>
            <option value="">Select Season</option>
            <?= fetchScheduleOptions($conn, 'season_id') ?>
        </select>

        <label for="series_id">Series ID:</label>
        <select name="series_id" id="series_id" required>
            <option value="">Select Series</option>
            <?= fetchScheduleOptions($conn, 'series_id') ?>
        </select>

        <label for="match_no">Match No:</label>
        <select name="match_no" id="match_no" required>
            <option value="">Select Match</option>
            <?= fetchScheduleOptions($conn, 'match_no') ?>
        </select>

        <label for="team_1">Team 1:</label>
        <select name="team_1" id="team_1" required>
            <option value="">Select Team 1</option>
            <?= fetchScheduleOptions($conn, 'team_1') ?>
        </select>

        <label for="team_2">Team 2:</label>
        <select name="team_2" id="team_2" required>
            <option value="">Select Team 2</option>
            <?= fetchScheduleOptions($conn, 'team_2') ?>
        </select>

        <input type="submit" value="Submit">
    </form>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
