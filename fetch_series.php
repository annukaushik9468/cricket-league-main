<?php
include 'dbconnection.php';

if (isset($_POST['season_id'])) {
    $season_id = $_POST['season_id'];

    $conn = new mysqli('localhost', 'root', '', 'cric_stats');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch series IDs that exist in matches for the selected season
    $query = "SELECT DISTINCT s.id, s.title 
              FROM tbl_series s 
              INNER JOIN tbl_matches m ON s.id = m.series_id 
              WHERE m.season_id = '$season_id'";

    $result = $conn->query($query);

    echo '<option value="">Select Series</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
    }

    $conn->close();
}
?>
