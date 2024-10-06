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

$total_runs = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected player from the form
    $id = $_POST['id'];

    // Fetch the player's record from tbl_team_record
    $teamRecordQuery = "SELECT 
                            SUM(dot_ball) AS total_dot_balls,
                            SUM(one_run) AS total_one_runs,
                            SUM(two_run) AS total_two_runs,
                            SUM(three_run) AS total_three_runs,
                            SUM(four_run) AS total_four_runs,
                            SUM(six_run) AS total_six_runs
                        FROM tbl_team_record 
                        WHERE battsman = (SELECT id FROM tbl_player WHERE id = ?)";
    
    $stmt = $conn->prepare($teamRecordQuery);
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
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
        echo "No data found for the selected batsman.";
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fetch Player Runs</title>
</head>
<body>

<h2>Select Player</h2>
<form method="post" action="">
    <label for="player">Player:</label>
    <select name="id" id="player" class="form-control">
        <?php while($player = $playersResult->fetch_assoc()) { ?>
            <option value="<?php echo $player['id']; ?>"><?php echo $player['name']; ?></option>
        <?php } ?>
    </select>
    <br><br>
    
    <input type="submit" value="Submit" class="btn btn-primary">
</form>

<?php if ($total_runs > 0): ?>
    <h3 class="my-2">Total runs for the selected batsman: <?php echo $total_runs; ?></h3>
<?php endif; ?>

</body>
<?php include 'footer.php'; ?>
</html>
