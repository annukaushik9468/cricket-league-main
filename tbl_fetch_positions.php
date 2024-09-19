<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "cric_stats");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['player_id']) && isset($_POST['position'])) {
    $player_id = intval($_POST['player_id']);
    $position = intval($_POST['position']);
    $query = "UPDATE tbl_teamplayer SET position = $position WHERE id = $player_id";
    if ($conn->query($query) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
}

$conn->close();
?>
