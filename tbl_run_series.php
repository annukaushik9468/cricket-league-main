<?php include 'header.php'; ?>
<?php
// Database connection
$host = 'localhost';  // Your database host
$username = 'root';    // Your database username
$password = '';        // Your database password
$database = 'cric_stats';  // Your database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch players from tbl_player
$playersQuery = "SELECT id, name FROM tbl_player";
$playersResult = $conn->query($playersQuery);

// Fetch series from tbl_series
$seriesQuery = "SELECT id, title FROM tbl_series";
$seriesResult = $conn->query($seriesQuery);

// Fetch teams from tbl_team
$teamsQuery = "SELECT id, title FROM tbl_team"; // Adjust according to your table structure
$teamsResult = $conn->query($teamsQuery);

$total_runs = 0;
$player_name = '';
$selected_team = '';
$breakdown = []; // To store the breakdown of runs

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected player, series, and team from the form
    $id = $_POST['id'];
    $series_id = $_POST['series_id'];
    $selected_team = $_POST['team']; // Get selected team

    // Fetch the player's name
    $playerQuery = "SELECT name FROM tbl_player WHERE id = ?";
    $stmtPlayer = $conn->prepare($playerQuery);
    $stmtPlayer->bind_param("i", $id);
    $stmtPlayer->execute();
    $playerResult = $stmtPlayer->get_result();
    if ($playerResult->num_rows > 0) {
        $playerData = $playerResult->fetch_assoc();
        $player_name = $playerData['name'];
    }

    // Fetch the player's record from tbl_team_record against the selected team
    $teamRecordQuery = "SELECT 
                            SUM(dot_ball) AS total_dot_balls,
                            SUM(one_run) AS total_one_runs,
                            SUM(two_run) AS total_two_runs,
                            SUM(three_run) AS total_three_runs,
                            SUM(four_run) AS total_four_runs,
                            SUM(six_run) AS total_six_runs
                        FROM tbl_team_record 
                        WHERE battsman = ? 
                        AND series_id = ?
                        AND (team_1 = ? OR team_2 = ?)"; // Include both teams in the condition

    $stmt = $conn->prepare($teamRecordQuery);
    $stmt->bind_param("iiss", $id, $series_id, $selected_team, $selected_team);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $total_runs = 
            $row['total_dot_balls'] * 0 +
            $row['total_one_runs'] * 1 +
            $row['total_two_runs'] * 2 +
            $row['total_three_runs'] * 3 +
            $row['total_four_runs'] * 4 +
            $row['total_six_runs'] * 6;

        // Store the breakdown for later display
        $breakdown = [
            'total_dot_balls' => $row['total_dot_balls'],
            'total_one_runs' => $row['total_one_runs'],
            'total_two_runs' => $row['total_two_runs'],
            'total_three_runs' => $row['total_three_runs'],
            'total_four_runs' => $row['total_four_runs'],
            'total_six_runs' => $row['total_six_runs'],
        ];
    } else {
        echo "No data found for the selected batsman in this series against the selected team.";
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fetch Player Runs</title>
    <style>
        .clickable {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }
        .breakdown {
            display: none; /* Initially hidden */
            margin-top: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
    <script>
        function toggleBreakdown() {
            var breakdown = document.getElementById('breakdown');
            if (breakdown.style.display === 'none') {
                breakdown.style.display = 'block';
            } else {
                breakdown.style.display = 'none';
            }
        }
    </script>
</head>
<body>

<h2>Select Player, Series, and Team</h2>
<form method="post" action="">
    <label for="player">Player:</label>
    <select name="id" id="player" class="form-control my-2 mt-2">
        <?php while($player = $playersResult->fetch_assoc()) { ?>
            <option value="<?php echo $player['id']; ?>"><?php echo $player['name']; ?></option>
        <?php } ?>
    </select>
 
    <label for="series">Series:</label>
    <select name="series_id" id="series" class="form-control my-2 mt-2">
        <?php while($series = $seriesResult->fetch_assoc()) { ?>
            <option value="<?php echo $series['id']; ?>"><?php echo $series['title']; ?></option>
        <?php } ?>
    </select>

    <label for="team">Team:</label>
    <select name="team" id="team" class="form-control my-2 mt-2">
        <?php while($team = $teamsResult->fetch_assoc()) { ?>
            <option value="<?php echo $team['id']; ?>"><?php echo $team['title']; ?></option>
        <?php } ?>
    </select>
    
    <input type="submit" value="Submit" class="btn btn-success mt-2">
</form>

<?php if ($total_runs > 0): ?>
    <h3 class="my-2">
        Total runs for <?php echo $player_name; ?> against <?php echo $selected_team; ?>: 
        <span class="clickable" onclick="toggleBreakdown()"><?php echo $total_runs; ?></span>
    </h3>
    <div id="breakdown" class="breakdown">
        <h4>Breakdown:</h4>
        <ul>
            <li>Dot Balls: <?php echo $breakdown['total_dot_balls']; ?></li>
            <li>One Runs: <?php echo $breakdown['total_one_runs']; ?></li>
            <li>Two Runs: <?php echo $breakdown['total_two_runs']; ?></li>
            <li>Three Runs: <?php echo $breakdown['total_three_runs']; ?></li>
            <li>Four Runs: <?php echo $breakdown['total_four_runs']; ?></li>
            <li>Six Runs: <?php echo $breakdown['total_six_runs']; ?></li>
        </ul>
    </div>
<?php endif; ?>

</body>
</html>
<?php include 'footer.php'; ?>
