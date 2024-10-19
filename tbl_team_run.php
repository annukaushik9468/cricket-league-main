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

$total_runs = 0;
$player_name = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected player and series from the form
    $id = $_POST['id'];
    $series_id = $_POST['series_id'];

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

    // Fetch the player's record from tbl_team_record
    $teamRecordQuery = "SELECT 
                            SUM(dot_ball) AS total_dot_balls,
                            SUM(one_run) AS total_one_runs,
                            SUM(two_run) AS total_two_runs,
                            SUM(three_run) AS total_three_runs,
                            SUM(four_run) AS total_four_runs,
                            SUM(six_run) AS total_six_runs
                        FROM tbl_team_record 
                        WHERE battsman = ? 
                        AND series_id = ?";
    
    $stmt = $conn->prepare($teamRecordQuery);
    $stmt->bind_param("ii", $id, $series_id);
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
    } else {
        echo "No data found for the selected batsman in this series.";
    }
    
    $stmt->close();
}

if (isset($_GET['view_runs']) && !empty($_GET['id'])) {
    $selected_id = $_GET['id'];

    // Fetch team and match details for batting and fielding
    $teamSelectionQuery = "SELECT team_1, team_2 FROM tbl_teamselection WHERE id = ?";
    $stmt = $conn->prepare($teamSelectionQuery);
    $stmt->bind_param("i", $selected_id);
    $stmt->execute();
    $teamResult = $stmt->get_result();
    
    if ($teamResult->num_rows > 0) {
        $teams = $teamResult->fetch_assoc();
        $team1 = $teams['team_1'];
        $team2 = $teams['team_2'];
        
        // Check batting records
        $battingQuery = "SELECT 
                            SUM(dot_ball) AS total_dot_balls,
                            SUM(one_run) AS total_one_runs,
                            SUM(two_run) AS total_two_runs,
                            SUM(three_run) AS total_three_runs,
                            SUM(four_run) AS total_four_runs,
                            SUM(six_run) AS total_six_runs
                        FROM tbl_team_record 
                        WHERE battsman = ? 
                        AND team_1 = ?";
        
        $stmt_batting = $conn->prepare($battingQuery);
        $stmt_batting->bind_param("is", $selected_id, $team1);
        $stmt_batting->execute();
        $battingResult = $stmt_batting->get_result();
        
        if ($battingResult->num_rows > 0) {
            $battingRow = $battingResult->fetch_assoc();
            $battingRuns = [
                'dot_ball' => $battingRow['total_dot_balls'],
                'one_run' => $battingRow['total_one_runs'],
                'two_run' => $battingRow['total_two_runs'],
                'three_run' => $battingRow['total_three_runs'],
                'four_run' => $battingRow['total_four_runs'],
                'six_run' => $battingRow['total_six_runs']
            ];
        }

        // Check fielding records including wides and wickets
        $fieldingQuery = "SELECT 
                            SUM(wide) AS total_wides,
                            SUM(wicket) AS total_wickets
                        FROM tbl_team_record 
                        WHERE bowler = ? 
                        AND team_2 = ?";
        
        $stmt_fielding = $conn->prepare($fieldingQuery);
        $stmt_fielding->bind_param("is", $selected_id, $team2);
        $stmt_fielding->execute();
        $fieldingResult = $stmt_fielding->get_result();
        
        if ($fieldingResult->num_rows > 0) {
            $fieldingRow = $fieldingResult->fetch_assoc();
            $fieldingStats = [
                'total_wides' => $fieldingRow['total_wides'],
                'total_wickets' => $fieldingRow['total_wickets']
            ];
        }
    }
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
    </style>
</head>
<body>

<h2>Select Player and Series</h2>
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
    
    
    <input type="submit" value="Submit" class="btn btn-success mt-2">
</form>

<?php if ($total_runs > 0): ?>
    <h3 class="my-2">Total runs for <?php echo $player_name; ?>: 
        <span class="clickable" onclick="location.href='?view_runs=1&id=<?php echo $id; ?>'"><?php echo $total_runs; ?></span>
    </h3>
<?php endif; ?>

<?php if (isset($battingRuns)): ?>
    <h4 class="my-4 btn btn-dark">Batting Runs</h4>
    <ul>
        <li>Dot Balls: <?php echo $battingRuns['dot_ball']; ?></li>
        <li>One Runs: <?php echo $battingRuns['one_run']; ?></li>
        <li>Two Runs: <?php echo $battingRuns['two_run']; ?></li>
        <li>Three Runs: <?php echo $battingRuns['three_run']; ?></li>
        <li>Four Runs: <?php echo $battingRuns['four_run']; ?></li>
        <li>Six Runs: <?php echo $battingRuns['six_run']; ?></li>
    </ul>
<?php endif; ?>

<?php if (isset($fieldingStats)): ?>
    <h4 class="my-3 btn btn-dark">Fielding Stats:</h4>
    <ul>
        <li>Total Wides: <?php echo $fieldingStats['total_wides']; ?></li>
        <li>Total Wickets: <?php echo $fieldingStats['total_wickets']; ?></li>
    </ul>
<?php endif; ?>

</body>
</html>
<?php include 'footer.php'; ?>
