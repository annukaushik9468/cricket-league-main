<?php
include 'header.php';

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'cric_stats';
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['player_id']) && isset($_GET['opponent_id']) && isset($_GET['player_team'])) {
    $player_id = (int)$_GET['player_id'];
    $opponent_id = (int)$_GET['opponent_id'];
    $player_team = $_GET['player_team'];

    $stmt = $conn->prepare("SELECT 
            stadium_name, 
            total_runs, 
            total_balls, 
            total_outs 
        FROM tbl_team_record
        WHERE player_id = ? AND opponent_id = ? AND player_team = ?");

    $stmt->bind_param('iis', $player_id, $opponent_id, $player_team);
    $stmt->execute();
    $result = $stmt->get_result();

    $details = $result->fetch_assoc();

    echo json_encode($details);
}

$conn->close();
?>
