<?php
include 'dbconnection.php';

if (isset($_GET['series_id'])) {
    $series_id = $_GET['series_id'];
    
    // Fetch teams based on the selected series_id
    $query = "SELECT * FROM tbl_team WHERE series_id = '$series_id'";
    $result = mysqli_query($con, $query);

    $teams = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $teams[] = $row;
    }

    // Return the teams as a JSON response
    echo json_encode($teams);
}
?>
