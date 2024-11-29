<?php
include 'header.php';
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'cric_stats';
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$player_query = "SELECT id, name FROM tbl_player";
$series_query = "SELECT id, title FROM tbl_series";
$players = $conn->query($player_query);
$series = $conn->query($series_query);

$records = null;
$player_id = null;
$series_id = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['player_id']) && isset($_POST['series_id'])) {
        $player_id = $_POST['player_id'];
        $series_id = $_POST['series_id'];

        $record_query = "
            SELECT 
                s.title AS season_title, 
                COUNT(DISTINCT tr.match_no) AS total_matches,
                SUM(tr.four_run) AS four_runs,
                SUM(tr.six_run) AS six_runs,
                SUM(tr.one_run + tr.two_run * 2 + tr.three_run * 3 + tr.four_run * 4 + tr.six_run * 6) AS total_runs,
                SUM(tr.dot_ball + tr.one_run + tr.two_run + tr.three_run + tr.four_run + tr.six_run) AS total_balls,
                SUM(CASE WHEN tr.wicket > 0 THEN 1 ELSE 0 END) AS total_outs,
                SUM(CASE WHEN tr.wicket = 0 THEN 1 ELSE 0 END) AS total_not_outs
            FROM tbl_team_record tr
            JOIN tbl_player p ON tr.bowler = p.id
            JOIN tbl_season s ON tr.season_id = s.id
            WHERE tr.battsman = $player_id AND tr.series_id = $series_id
            GROUP BY s.title
        ";

        $records = $conn->query($record_query);
    }

    // Updated query for fetching match details by opponent team
    if (isset($_POST['match_no']) && isset($_POST['player_id']) && isset($_POST['team_id'])) {
        $match_no = $_POST['match_no'];
        $player_id = $_POST['player_id'];
        $team_id = $_POST['team_id'];  // Get the opponent team ID

        $match_details_query = "
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
            WHERE tr.match_no = $match_no AND tr.battsman = $player_id 
            AND (t1.id = $team_id OR t2.id = $team_id)  // Filter by the opponent team
            GROUP BY tr.match_no, p.name, t1.title, t2.title
        ";

        $match_details = $conn->query($match_details_query);
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Player Performance by Series</title>
    <style type="text/css">
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        form { margin-bottom: 30px; padding: 15px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        form label { display: block; margin-bottom: 5px; font-weight: bold; }
        form select { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        form button { padding: 10px 20px; background-color: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        form button:hover { background-color: #218838; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        table th, table td { padding: 10px; text-align: center; }
        table thead { background-color: #f8f9fa; }
        table th { font-weight: bold; background-color: #007bff; color: #ffffff; }
        table tr:nth-child(even) { background-color: #f2f2f2; }
        table tr:hover { background-color: #d1ecf1; }
        /* Modal style */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 1000; }
        .modal-content { margin: 15% auto; background-color: #fff; padding: 20px; border-radius: 5px; width: 80%; }
        .close-btn { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close-btn:hover, .close-btn:focus { color: black; text-decoration: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Select Player and Series</h2>
    <form method="POST" action="">
    <label for="player">Player:</label>
    <select name="player_id" id="player" required>
        <option value="">Select Player</option>
        <?php while ($row = $players->fetch_assoc()) { ?>
            <option value="<?= $row['id']; ?>" <?= (isset($player_id) && $player_id == $row['id']) ? 'selected' : ''; ?>><?= $row['name']; ?></option>
        <?php } ?>
    </select>

    <label for="series">Series:</label>
    <select name="series_id" id="series" required>
        <option value="">Select Series</option>
        <?php while ($row = $series->fetch_assoc()) { ?>
            <option value="<?= $row['id']; ?>" <?= (isset($series_id) && $series_id == $row['id']) ? 'selected' : ''; ?>><?= $row['title']; ?></option>
        <?php } ?>
    </select>

    
    

    <button type="submit">Fetch Performance</button>
</form>

    <?php if (isset($records) && $records->num_rows > 0) { ?>
   <h3>Series Performance Details</h3>
    <table>
        <thead>
            <tr>
                <th>Season</th>
                <th>Total Matches</th>
                <th>Four Runs</th>
                <th>Six Runs</th>
                <th>Total Runs</th>
                <th>Total Balls</th>
                <th>Out</th>
                <th>Not Out</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $records->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['season_title']; ?></td>
                    <td><a href="javascript:void(0);" class="viewMatchDetails" data-match-no="<?= $row['total_matches']; ?>"><?= $row['total_matches']; ?></a></td>
                    <td><?= $row['four_runs']; ?></td>
                    <td><?= $row['six_runs']; ?></td>
                    <td><?= $row['total_runs']; ?></td>
                    <td><?= $row['total_balls']; ?></td>
                    <td><?= $row['total_outs']; ?></td>
                    <td><?= $row['total_not_outs']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

    <!-- Modal for match details -->
    <div class="modal" id="matchDetailsModal">
        <div class="modal-content">
            <span class="close-btn" id="closeModal">&times;</span>
            <h3>Opponent Team Performance</h3>
            <p><strong>Match No:</strong> <span id="matchNo"></span></p>
            <p><strong>Opponent Team:</strong> <span id="opponentTeam"></span></p>
            <p><strong>Total Runs:</strong> <span id="opponentTotalRuns"></span></p>
            <p><strong>Total Balls:</strong> <span id="opponentTotalBalls"></span></p>
            <p><strong>Outs:</strong> <span id="opponentOuts"></span></p>
            <p><strong>Details:</strong></p>
            <ul>
                <li><strong>Four Runs:</strong> <span id="opponentFourRuns"></span></li>
                <li><strong>Six Runs:</strong> <span id="opponentSixRuns"></span></li>
            </ul>
        </div>
    </div>

    <script type="text/javascript">
        // Modal script for showing match details
        document.querySelectorAll('.viewMatchDetails').forEach(function(element) {
            element.addEventListener('click', function() {
                var matchNo = this.getAttribute('data-match-no');

                // AJAX request to fetch opponent team data
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'get_opponent_performance.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        var data = JSON.parse(xhr.responseText);
                        document.getElementById('matchNo').textContent = data.match_no;
                        document.getElementById('opponentTeam').textContent = data.opponent_team;
                        document.getElementById('opponentTotalRuns').textContent = data.total_runs;
                        document.getElementById('opponentTotalBalls').textContent = data.total_balls;
                        document.getElementById('opponentOuts').textContent = data.outs;
                        document.getElementById('opponentFourRuns').textContent = data.four_runs;
                        document.getElementById('opponentSixRuns').textContent = data.six_runs;
                        document.getElementById('matchDetailsModal').style.display = 'block';
                    }
                };
                xhr.send('match_no=' + matchNo);
            });
        });

        // Close modal
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('matchDetailsModal').style.display = 'none';
        });
    </script>
</body>
</html>
