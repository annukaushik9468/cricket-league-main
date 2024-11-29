<?php

// dbconnection.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cric_stats";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// Check if the ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Step 1: Get the data of the schedule to be archived
    $query = "SELECT * FROM tbl_schedule WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_array($result);

        if ($row) {
            // Step 2: Insert the record into the archived schedule table
            $match_no = $row['match_no'];
            $series_id = $row['series_id'];
            $season_id = $row['season_id'];
            $team_1 = $row['team_1'];
            $team_2 = $row['team_2'];
            $stadium_id = $row['stadium_id'];

            $archive_query = "INSERT INTO tbl_archived_schedule (match_no, series_id, season_id, team_1, team_2, stadium_id)
                              VALUES ('$match_no', '$series_id', '$season_id', '$team_1', '$team_2', '$stadium_id')";

            if (mysqli_query($conn, $archive_query)) {
                // Step 3: Delete the record from tbl_schedule
                $delete_query = "DELETE FROM tbl_schedule WHERE id = '$id'";
                if (mysqli_query($conn, $delete_query)) {
                    // Redirect to the schedule page after success
                    header("Location: tbl_schedulelisting.php?status=archived");
                } else {
                    echo "Error deleting schedule: " . mysqli_error($conn);
                }
            } else {
                echo "Error archiving schedule: " . mysqli_error($conn);
            }
        } else {
            echo "Schedule not found.";
        }
    } else {
        echo "Error retrieving schedule: " . mysqli_error($conn);
    }
} else {
    echo "No ID provided.";
}

// Close the database connection
mysqli_close($conn);
?>
