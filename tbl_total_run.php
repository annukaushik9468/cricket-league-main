<?php
include 'header.php'; 
// Database connection
$host = 'localhost'; 
$user = 'root'; 
$pass = ''; 
$dbname = 'cric_stats'; // replace with your database name
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

        // Fetch matches, bowler names, run details, and out status from tbl_team_record
        $record_query = "
            SELECT 
                tr.match_no, 
                p.name AS bowler, 
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
            WHERE tr.battsman = $player_id AND tr.series_id = $series_id
            GROUP BY tr.match_no, p.name
        ";

        $records = $conn->query($record_query);

        // Error checking for the query
        if (!$records) {
            die("Error in SQL query: " . $conn->error);
        }
    }
}
?>

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
        <h3>Performance Details</h3>
        <table border="1">
            <thead>
                <tr>
                    <th width="90px">Match No</th>
                    <th width="90px">Bowler Name</th>
                   <!--  <th>Dot Balls</th>
                    <th>1 Run</th>
                    <th>2 Runs</th>
                    <th>3 Runs</th>
                    <th>4 Runs</th>
                    <th>6 Runs</th> -->
                    <th width="90px">Total Runs</th>
                    <th width="90px">Total Balls</th>
                    <th width="90px">Player Out</th> <!-- New column for player out status -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $records->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['match_no']; ?></td>
                        <td><?= $row['bowler']; ?></td>
                        <!-- <td><?= $row['dot_ball']; ?></td>
                        <td><?= $row['one_run']; ?></td>
                        <td><?= $row['two_run']; ?></td>
                        <td><?= $row['three_run']; ?></td>
                        <td><?= $row['four_run']; ?></td>
                        <td><?= $row['six_run']; ?></td> -->
                        <td><?= $row['total_runs']; ?></td>
                        <td><?= $row['total_balls']; ?></td>
                        <td><?= $row['is_out'] ? 'Yes' : 'No'; ?></td> <!-- Check if player is out -->
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
