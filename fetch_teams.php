<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cric_stats";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch teams based on selected series
if (isset($_GET['series'])) {
    $series_name = $_GET['series'];
    $teams = [];
    
    $sql = "SELECT DISTINCT team_1 AS team FROM tbl_team_record WHERE series_id = ? UNION SELECT DISTINCT team_2 AS team FROM tbl_team_record WHERE series_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $series_id, $series_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $teams[] = $row['team'];
    }
    
    echo json_encode($teams);
}

$conn->close();
?>
