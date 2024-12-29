<?php
include 'dbconnection.php';

if (isset($_POST['series_id'])) {
    $series_id = $_POST['series_id'];

    // Fetch matches for the selected series
    $conn = new mysqli('localhost', 'root', '', 'cric_stats');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT 
                m.id AS match_id, 
                t1.title AS team1, 
                t2.title AS team2 
              FROM tbl_matches m 
              JOIN tbl_team t1 ON m.team_1 = t1.id 
              JOIN tbl_team t2 ON m.team_2 = t2.id 
              WHERE m.series_id = '$series_id'";

    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['match_id'] . '">' . $row['team1'] . ' vs ' . $row['team2'] . '</option>';
        }
    } else {
        echo '<option value="">No matches found for selected series</option>';
    }

    $conn->close();
}
?>
