<?php
include 'header.php';
include 'dbconnect.php';


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

    if (isset($_POST['match_no']) && isset($_POST['player_id'])) {
        $match_no = $_POST['match_no'];
        $player_id = $_POST['player_id'];

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
                <td>
                    <a href="" class="match-link" data-match-no="<?= $row['total_matches']; ?>">
                        <?= $row['total_matches']; ?>
                    </a>
                </td>
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


  <!-- Existing content remains -->
<!-- Add a modal for match details -->
<div id="matchDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Match Details</h3>
        <table id="matchDetailsTable">
            <thead>
                <tr>
                    <th>Match No</th>
                    <th>Bowler</th>
                    <th>Team 1</th>
                    <th>Team 2</th>
                    <th>Dot Balls</th>
                    <th>One Runs</th>
                    <th>Two Runs</th>
                    <th>Three Runs</th>
                    <th>Four Runs</th>
                    <th>Six Runs</th>
                    <th>Total Runs</th>
                    <th>Total Balls</th>
                    <th>Out</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('matchDetailsModal');
    const closeBtn = document.querySelector('.close-btn');

    document.querySelectorAll('.match-link').forEach(link => {
        link.addEventListener('click', event => {
            event.preventDefault();
            const matchNo = event.target.getAttribute('data-match-no');

            // Fetch match details using AJAX
            fetch(`fetch_match_details.php?match_no=${matchNo}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#matchDetailsTable tbody');
                    tableBody.innerHTML = '';
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${row.match_no}</td>
                            <td>${row.bowler}</td>
                            <td>${row.team_1}</td>
                            <td>${row.team_2}</td>
                            <td>${row.dot_ball}</td>
                            <td>${row.one_run}</td>
                            <td>${row.two_run}</td>
                            <td>${row.three_run}</td>
                            <td>${row.four_run}</td>
                            <td>${row.six_run}</td>
                            <td>${row.total_runs}</td>
                            <td>${row.total_balls}</td>
                            <td>${row.is_out}</td>
                        `;
                        tableBody.appendChild(tr);
                    });
                    modal.style.display = 'block';
                });
        });
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', event => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
});
</script>




</body>
</html>