<?php
include 'header.php'; 
include 'dbconnect.php';

// Fetch players and series for dropdown
$player_query = "SELECT id, name FROM tbl_player";
$series_query = "SELECT id, title FROM tbl_series";

$players = $conn->query($player_query);
$series = $conn->query($series_query);

// Initialize variables
$records = null;
$player_id = null;
$series_id = null;

// After form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if player_id and series_id are set
    if (isset($_POST['player_id']) && isset($_POST['series_id'])) {
        $player_id = $_POST['player_id'];
        $series_id = $_POST['series_id'];

        // Query to fetch performance data with team details
        $record_query = "
            SELECT 
                tr.match_no, 
                s.title AS season_title, 
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
            JOIN tbl_season s ON tr.season_id = s.id
            JOIN tbl_team t1 ON tr.team_1 = t1.id
            JOIN tbl_team t2 ON tr.team_2 = t2.id
            WHERE tr.battsman = $player_id AND tr.series_id = $series_id
            GROUP BY tr.match_no, s.title, p.name, t1.title, t2.title
        ";

        $records = $conn->query($record_query);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Player Performance by Series</title>
    <style>
        /* Include your CSS here */
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // AJAX function to fetch match details
        function fetchMatchDetails(matchNo) {
            $.ajax({
                url: 'fetch_matches.php', 
                type: 'POST',
                data: { match_no: matchNo },
                success: function(data) {
                    $('#matchDetails').html(data);
                    $('#matchDetailsModal').show();
                }
            });
        }

        // Close modal
        function closeModal() {
            $('#matchDetailsModal').hide();
        }
    </script>
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
        <table>
            <thead>
                <tr>
                    <th>Bowler</th>
                    <th>Match No</th>
                    <th>Team 1</th>
                    <th>Team 2</th>
                    <th>Total Runs</th>
                    <th>Total Balls</th>
                    <th>Wickets</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $records->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['bowler']; ?></td>
                        <td><a href="javascript:void(0);" onclick="fetchMatchDetails(<?= $row['match_no']; ?>)"><?= $row['match_no']; ?></a></td>
                        <td><?= $row['team_1']; ?></td>
                        <td><?= $row['team_2']; ?></td>
                        <td><?= $row['total_runs']; ?></td>
                        <td><?= $row['total_balls']; ?></td>
                        <td><?= $row['is_out'] ? 'Yes' : 'No'; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

    <!-- Modal to display match details -->
    <div id="matchDetailsModal" style="display:none;">
        <div id="matchDetails"></div>
        <button onclick="closeModal()">Close</button>
    </div>
</body>
</html>

<?php $conn->close(); ?>

<?php include 'footer.php'; ?>