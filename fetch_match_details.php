<?php
include 'dbconnect.php';

if (isset($_GET['match_no'])) {
    $match_no = intval($_GET['match_no']);
    $query = "
        SELECT 
            tr.match_no, 
            p.name AS bowler, 
            t1.title AS team_1, 
            t2.title AS team_2,
            SUM(tr.dot_ball) AS dot_ball,
            SUM(tr.one_run) AS one_run, 
            SUM(tr.two_run) AS two_run, 
            SUM(tr.three_run) AS three_run, 
            SUM(tr.four_run) AS four_run, 
            SUM(tr.six_run) AS six_run, 
            (SUM(tr.one_run) + SUM(tr.two_run) * 2 + SUM(tr.three_run) * 3 + SUM(tr.four_run) * 4 + SUM(tr.six_run) * 6) AS total_runs,
            (SUM(tr.dot_ball) + SUM(tr.one_run) + SUM(tr.two_run) + SUM(tr.three_run) + SUM(tr.four_run) + SUM(tr.six_run)) AS total_balls,
            MAX(tr.wicket) AS is_out
        FROM tbl_team_record tr
        JOIN tbl_player p ON tr.bowler = p.id
        JOIN tbl_team t1 ON tr.team_1 = t1.id
        JOIN tbl_team t2 ON tr.team_2 = t2.id
        WHERE tr.match_no = $match_no
        GROUP BY tr.match_no, p.name, t1.title, t2.title
    ";

    $result = $conn->query($query);
    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    echo json_encode($data);
}
?>
