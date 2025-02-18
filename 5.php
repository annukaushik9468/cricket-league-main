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

// Fetch players and series
$player_query = "SELECT id, name FROM tbl_player";
$series_query = "SELECT id, title FROM tbl_series";
$players = $conn->query($player_query);
$series = $conn->query($series_query);

$records = null;
$player_id = $series_id = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['player_id'], $_POST['series_id'])) {
    $player_id = (int)$_POST['player_id'];
    $series_id = (int)$_POST['series_id'];

    // Fetch player's series performance
    $stmt = $conn->prepare("
        SELECT 
            s.title AS season_title,
            COUNT(DISTINCT tr.match_no) AS total_matches,
            SUM(tr.four_run) AS four_runs,
            SUM(tr.six_run) AS six_runs,
            SUM(tr.one_run + tr.two_run * 2 + tr.three_run * 3 + tr.four_run * 4 + tr.six_run * 6) AS total_runs,
            SUM(tr.dot_ball + tr.one_run + tr.two_run + tr.three_run + tr.four_run + tr.six_run) AS total_balls,
            SUM(CASE WHEN tr.wicket IS NOT NULL THEN 1 ELSE 0 END) AS total_outs,  -- Count outs
            SUM(CASE WHEN tr.wicket IS NULL THEN 1 ELSE 0 END) AS total_not_outs   -- Count not outs
        FROM tbl_team_record tr
        JOIN tbl_season s ON tr.season_id = s.id
        WHERE tr.battsman = ? AND tr.series_id = ?
        GROUP BY s.title
    ");
    $stmt->bind_param('ii', $player_id, $series_id);
    $stmt->execute();
    $records = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Performance by Series</title>
    
</head>
<body>
    <h2>Select Player and Series</h2>
    <form method="POST">
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

    <?php if ($records && $records->num_rows > 0) { ?>
        <h3>Performance Details</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Season</th>
                    <th>Total Matches</th>
                    <th>Four Runs</th>
                    <th>Six Runs</th>
                    <th>Total Runs</th>
                    <th>Total Balls</th>
                      <th>Outs</th>
                    <th>Not Outs</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $records->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['season_title']; ?></td>
                        <td><a href="#" class="match-link" data-player-id="<?= $player_id; ?>" data-series-id="<?= $series_id; ?>"><?= $row['total_matches']; ?></a></td>
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
    
<!-- Modal for Match Details -->
<div id="matchDetailsModal" style="display:none;">
    <h3>Match Details</h3>
    <table border="1" id="matchDetailsTable">
        <thead>
            <tr>
                <th>Opponent Team</th>
                <th>Total Matches</th>
                <th>Total Runs</th>
                <th>Total Balls</th>
                <th>Four Runs</th>
                <th>Six Runs</th>
                <th>Outs</th>
                <th>Not Outs</th>
                <th>Stadium</th> <!-- Added Stadium column -->
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <button onclick="document.getElementById('matchDetailsModal').style.display='none';">Close</button>
</div>

<script>
document.querySelectorAll('.match-link').forEach(link => {
    link.addEventListener('click', event => {
        event.preventDefault();
        const playerId = event.target.getAttribute('data-player-id');
        const seriesId = event.target.getAttribute('data-series-id');

        // Fetch match data for the player and series
        fetch(`1.php?player_id=${playerId}&series_id=${seriesId}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#matchDetailsTable tbody');
                tableBody.innerHTML = ''; // Clear previous data

                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="9">No data found</td></tr>';
                    return;
                }

                data.forEach(row => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${row.player_team} vs ${row.opponent_team}</td>
        <td>${row.total_matches}</td>
        <td>${row.total_runs}</td>
        <td>${row.total_balls}</td>
        <td>${row.four_runs}</td>
        <td>${row.six_runs}</td>
        <td>${row.total_outs}</td>
        <td>${row.total_not_outs}</td>
        <td>${row.stadium_name}</td>  <!-- Correctly display the stadium name -->
    `;
    tableBody.appendChild(tr);
});

                document.getElementById('matchDetailsModal').style.display = 'block';
            })
            .catch(error => console.error('Error fetching match details:', error));
    });
});
</script>




    <?php include'footer.php'; ?>
</body>
</html>
