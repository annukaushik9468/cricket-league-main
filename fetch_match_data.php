<?php
include 'dbconnection.php';

$team_1 = mysqli_real_escape_string($con, $_POST['team_1']);
$team_2 = mysqli_real_escape_string($con, $_POST['team_2']);
$season_id = mysqli_real_escape_string($con, $_POST['season_id']);
$series_id = mysqli_real_escape_string($con, $_POST['series_id']);
$match_no = mysqli_real_escape_string($con, $_POST['match_no']);

$query = "SELECT batting_team, toss_winner FROM tbl_matches 
          WHERE team_1 = '$team_1' AND team_2 = '$team_2' 
          AND season_id = '$season_id' AND series_id = '$series_id' 
          AND match_no = '$match_no'";

$result = mysqli_query($con, $query);
$data = mysqli_fetch_assoc($result);

echo json_encode($data);
?>
