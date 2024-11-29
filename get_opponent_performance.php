<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'cric_stats';
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['match_no'])) {
    $match_no = $_POST['match_no'];

    // Query to get the opponent team's performance
    $query = "
        SELECT 
            t1.title AS team_1, 
            t2.title AS team_2, 
            SUM(tr.four_run) AS four_runs,
            SUM(tr.six_run) AS six_runs,
            SUM(tr.one_run + tr.two_run * 2 + tr.three_run * 3 + tr.four_run * 4 + tr.six_run * 6) AS total_runs,
            SUM(tr.dot_ball + tr.one_run + tr.two_run + tr.three_run + tr.four_run + tr.six_run) AS total_balls,
            SUM(CASE WHEN tr.wicket > 0 THEN 1 ELSE 0 END) AS outs
        FROM tbl_team_record tr
        JOIN tbl_team t1 ON tr.team_1 = t1.id
        JOIN tbl_team t2 ON tr.team_2 = t2.id
        WHERE tr.match_no = $match_no
        GROUP BY t1.title, t2.title
    ";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $data = [
            'match_no' => $match_no,
            'opponent_team' => $row['team_1'], // Adjust to return the opponent team properly
            'total_runs' => $row['total_runs'],
            'total_balls' => $row['total_balls'],
            'outs' => $row['outs'],
            'four_runs' => $row['four_runs'],
            'six_runs' => $row['six_runs']
        ];
        echo json_encode($data); // Return the data as JSON
    } else {
        echo json_encode([]);
    }
}
?>
