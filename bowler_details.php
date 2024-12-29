<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'cric_stats';
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['player_id'], $_GET['series_id'])) {
    $player_id = (int)$_GET['player_id'];
    $series_id = (int)$_GET['series_id'];

    
    $query = "
        SELECT 
            b.name AS bowler_name,
            SUM(tr.one_run + tr.two_run * 2 + tr.three_run * 3 + tr.four_run * 4 + tr.six_run * 6) AS total_runs,
            SUM(tr.dot_ball + tr.one_run + tr.two_run + tr.three_run + tr.four_run + tr.six_run) AS total_balls,
            SUM(CASE WHEN tr.wicket IS NOT NULL THEN 1 ELSE 0 END) AS total_outs
        FROM tbl_team_record tr
        JOIN tbl_player b ON tr.bowler = b.id
        WHERE tr.battsman = ? AND tr.series_id = ? AND tr.team_2 = ?
        GROUP BY b.name
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $player_id, $series_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
} else {
    echo json_encode([]);
}

$conn->close();
?>
