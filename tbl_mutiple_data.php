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
$total_matches = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['player_id']) && isset($_POST['series_id'])) {
        $player_id = $_POST['player_id'];
        $series_id = $_POST['series_id'];

        $match_count_query = "SELECT COUNT(DISTINCT match_no) AS total_matches FROM tbl_team_record WHERE series_id = $series_id";
        $match_count_result = $conn->query($match_count_query);
        $total_matches = $match_count_result->fetch_assoc()['total_matches'] ?? 0;

        $record_query = "
            SELECT p.name AS bowler, COUNT(DISTINCT tr.match_no) AS matches_played,
                SUM(tr.dot_ball) AS dot_ball, SUM(tr.one_run) AS one_run,
                SUM(tr.two_run) AS two_run, SUM(tr.three_run) AS three_run,
                SUM(tr.four_run) AS four_run, SUM(tr.six_run) AS six_run,
                (SUM(tr.one_run) + SUM(tr.two_run) * 2 + SUM(tr.three_run) * 3 +
                SUM(tr.four_run) * 4 + SUM(tr.six_run) * 6) AS total_runs,
                (SUM(tr.dot_ball) + SUM(tr.one_run) + SUM(tr.two_run) + SUM(tr.three_run) +
                SUM(tr.four_run) + SUM(tr.six_run)) AS total_balls, MAX(tr.wicket) AS is_out
            FROM tbl_team_record tr
            JOIN tbl_player p ON tr.bowler = p.id
            WHERE tr.battsman = $player_id AND tr.series_id = $series_id
            GROUP BY p.name
        ";
        $records = $conn->query($record_query);
    }
}
?>
<!-- HTML form and table output here (similar to your first code) -->

<!DOCTYPE html>
<html>
<head>
    <title>Player Performance by Series</title>
    <style type="text/css">
        /* Style for the body */
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f4f4f9;
}

/* Style for the form */
form {
    margin-bottom: 30px;
    padding: 15px;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Form labels */
form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

/* Form select fields */
form select {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Submit button */
form button {
    padding: 10px 20px;
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

form button:hover {
    background-color: #218838;
}

/* Style for the table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

table th, table td {
    padding: 10px;
    text-align: center;
}

/* Header row */
table thead {
    background-color: #f8f9fa;
}

table th {
    font-weight: bold;
    background-color: #007bff;
    color: #ffffff;
}

/* Table row styling */
table tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Hover effect for rows */
table tr:hover {
    background-color: #d1ecf1;
}

    </style>
</head>
<body>
    <h2>Select Player and Series</h2>
    <form method="POST" action="">
        <label for="player">Player:</label>
        <select name="player_id" id="player" class="form-control my-3" required>
            <option value="">Select Player</option>
            <?php while ($row = $players->fetch_assoc()) { ?>
                <option value="<?= $row['id']; ?>" <?= (isset($player_id) && $player_id == $row['id']) ? 'selected' : ''; ?>><?= $row['name']; ?></option>
            <?php } ?>
        </select>

        <label for="series">Series:</label>
        <select name="series_id" id="series" class="form-control my-3" required>
            <option value="">Select Series</option>
            <?php while ($row = $series->fetch_assoc()) { ?>
                <option value="<?= $row['id']; ?>" <?= (isset($series_id) && $series_id == $row['id']) ? 'selected' : ''; ?>><?= $row['title']; ?></option>
            <?php } ?>
        </select>

        <button type="submit" class="btn btn-success">Fetch Performance</button>
    </form>

    <?php if (isset($records) && $records->num_rows > 0) { ?>
        <h3>Total Matches in Series: <?= $total_matches; ?></h3>
        <h3>Performance Details</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Bowler Name</th>
                    <th>Matches Played Against Player</th>
                    <th>Total Runs</th>
                    <th>Total Balls</th>
                    <th>Player Out</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $records->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['bowler']; ?></td>
                        <td><?= $row['matches_played']; ?></td>
                        <td><?= $row['total_runs']; ?></td>
                        <td><?= $row['total_balls']; ?></td>
                        <td><?= $row['is_out'] ? 'Yes' : 'No'; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } elseif (isset($records)) { ?>
        <p>No records found for the selected player and series.</p>
    <?php } ?>
</body>
</html>

<?php
// Close connection
$conn->close();
include 'footer.php';
?>