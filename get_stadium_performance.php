<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'cric_stats';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

if (isset($_GET['player_id'], $_GET['opponent_id'], $_GET['player_team'])) {
    $player_id = (int)$_GET['player_id'];
    $opponent_id = (int)$_GET['opponent_id'];
    $player_team = $_GET['player_team'];

    $stmt = $conn->prepare("
        SELECT 
            stadium,
            SUM(total_runs) AS total_runs,
            SUM(total_balls) AS total_balls,
            SUM(total_outs) AS total_outs
        FROM tbl_stadium_performance
        WHERE player_id = ? AND opponent_team = ? AND player_team = ?
    ");

    $stmt->bind_param('iis', $player_id, $opponent_id, $player_team);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Invalid parameters']);
}
?>
