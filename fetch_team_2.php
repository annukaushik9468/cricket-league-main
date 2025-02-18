<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "cric_stats");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['series_id'])) {
    $series_id = $_POST['series_id'];
    $team_1 = $_POST['team_1'];
    $team_2 = $_POST['team_2'];

    // Fetch teams based on series ID
    $query = "SELECT id, title FROM tbl_team WHERE series_id = $series_id";
    if($team_1!='' && $team_2=='')
    {
        $query .= " AND id in ($team_1)";
    }
    if($team_2!='' && $team_1=='')
    {
        $query .= " AND id in ($team_2)";
    }
    if($team_1!='' && $team_2!='')
    {
        $query .= " AND id in ($team_1,$team_2)";
    }
    // echo $query; die;
    $result = $conn->query($query);

    $teams = [];
    while ($row = $result->fetch_assoc()) {
        $teams[] = $row;
    }

    echo json_encode($teams);
}

$conn->close();
?>
