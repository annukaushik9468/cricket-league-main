<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "cric_stats");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch players for dropdowns
$query = "SELECT id, name FROM tbl_player";
$result = $conn->query($query);

// Fetch selected player's performance data
if (isset($_POST['submit'])) {
    $id = $_POST['player'];
    
    // Query to fetch player data from tbl_team_record
    $query = "SELECT dot_ball, one_run, two_run, three_run, four_run, six_run, wide, wicket 
              FROM tbl_team_record 
              WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    
    // Check if the prepare() failed
    if ($stmt === false) {
        die('Prepare() failed: ' . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($dot_ball, $one_run, $two_run, $three_run, $four_run, $six_run, $wide, $wicket);
    
    $total_runs = 0;
    while ($stmt->fetch()) {
        // Calculate the total runs
        $total_runs = (1 * $one_run) + (2 * $two_run) + (3 * $three_run) + (4 * $four_run) + (6 * $six_run);
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Total Runs and Stats</title>
</head>
<body>

<form method="POST" action="">
    <label for="player">Select Player:</label>
    <select name="player" id="player">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
        }
        ?>
    </select>

    <button type="submit" name="submit">Get Total Runs and Stats</button>
</form>

<?php
// Display player statistics if available
if (isset($total_runs)) {
    echo "<h3>Player Stats:</h3>";
    echo "<p>Total Runs: " . $total_runs . "</p>";
    echo "<p>Dot Balls: " . $dot_ball . "</p>";
    echo "<p>One Runs: " . $one_run . "</p>";
    echo "<p>Two Runs: " . $two_run . "</p>";
    echo "<p>Three Runs: " . $three_run . "</p>";
    echo "<p>Four Runs: " . $four_run . "</p>";
    echo "<p>Six Runs: " . $six_run . "</p>";
    echo "<p>Wides: " . $wide . "</p>";
    echo "<p>Wickets: " . $wicket . "</p>";
}
?>

</body>
</html>
