<?php
// Database connection
$host = 'localhost';  // Your database host
$username = 'root';   // Your database username
$password = '';       // Your database password
$database = 'cric_stats';  // Your database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get player ID and series ID from the URL
$player_id = $_GET['player_id'];
$series_id = $_GET['series_id'];

// Get player's name from tbl_player
$playerQuery = "SELECT name FROM tbl_player WHERE id = ?";
$stmt = $conn->prepare($playerQuery);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}
$stmt->bind_param("i", $player_id);
$stmt->execute();
$result = $stmt->get_result();
$player_row = $result->fetch_assoc();

if ($player_row) {
    $player_name = $player_row['name'];

    // Fetch batting performance from tbl_team_record
    $battingQuery = "SELECT 
                        SUM(dot_ball) AS total_dot_balls,
                        SUM(one_run) AS total_one_runs,
                        SUM(two_run) AS total_two_runs,
                        SUM(three_run) AS total_three_runs,
                        SUM(four_run) AS total_four_runs,
                        SUM(six_run) AS total_six_runs
                    FROM tbl_team_record 
                    WHERE battsman = ? AND series_id = ?";
    $stmt = $conn->prepare($battingQuery);
    if (!$stmt) {
        die("Batting query preparation failed: " . $conn->error);
    }
    $stmt->bind_param("si", $player_name, $series_id);
    $stmt->execute();
    $battingResult = $stmt->get_result();
    $battingRow = $battingResult->fetch_assoc();

    // Fetch fielding performance from tbl_team_record
    $fieldingQuery = "SELECT 
                        SUM(wide) AS total_wides,
                        SUM(wicket) AS total_wickets
                    FROM tbl_team_record 
                    WHERE fielder = ? AND series_id = ?";
    $stmt = $conn->prepare($fieldingQuery);
    if (!$stmt) {
        die("Fielding query preparation failed: " . $conn->error);
    }
    $stmt->bind_param("si", $player_name, $series_id);
    $stmt->execute();
    $fieldingResult = $stmt->get_result();
    $fieldingRow = $fieldingResult->fetch_assoc();

    echo "<h2>Performance of $player_name</h2>";
    echo "<h3>Batting Performance:</h3>";
    echo "Dot Balls: " . ($battingRow['total_dot_balls'] ?? 0) . "<br>";
    echo "One Runs: " . ($battingRow['total_one_runs'] ?? 0) . "<br>";
    echo "Two Runs: " . ($battingRow['total_two_runs'] ?? 0) . "<br>";
    echo "Three Runs: " . ($battingRow['total_three_runs'] ?? 0) . "<br>";
    echo "Four Runs: " . ($battingRow['total_four_runs'] ?? 0) . "<br>";
    echo "Six Runs: " . ($battingRow['total_six_runs'] ?? 0) . "<br>";

    echo "<h3>Fielding Performance:</h3>";
    echo "Total Wides: " . ($fieldingRow['total_wides'] ?? 0) . "<br>";
    echo "Total Wickets: " . ($fieldingRow['total_wickets'] ?? 0) . "<br>";
} else {
    echo "Player not found.";
}

$stmt->close();
$conn->close();
?>
