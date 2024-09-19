<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "cric_stats");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$season_id = isset($_POST['season_id']) ? intval($_POST['season_id']) : 0;
$series_id = isset($_POST['series_id']) ? intval($_POST['series_id']) : 0;
$team_id = isset($_POST['team_id']) ? intval($_POST['team_id']) : 0;

$query = "SELECT id, player_name, status FROM tbl_teamplayer WHERE season_id = $season_id AND series_id = $series_id AND team_id = $team_id";
$result = $conn->query($query);

$players = [];
while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}

echo json_encode($players);
$conn->close();
?>