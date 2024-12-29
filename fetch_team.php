<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "cric_stats");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['series_id'])) {
    $series_id = $_POST['series_id'];

    // Fetch teams based on series ID
    $result = $conn->query("SELECT id, title FROM tbl_team WHERE series_id = $series_id");

    $teams = [];
    while ($row = $result->fetch_assoc()) {
        $teams[] = $row;
    }

    echo json_encode($teams);
}

$conn->close();
?>
