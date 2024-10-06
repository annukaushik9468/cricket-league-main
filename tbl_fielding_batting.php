<?php include 'header.php'; ?>
<?php
// Database connection
$servername = "localhost"; // your database server
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "cric_stats"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch batsman names for dropdown
$battsmanQuery = "SELECT id, name FROM tbl_player"; // Adjust table and column names as necessary
$battsmanResult = $conn->query($battsmanQuery);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $battsmanId = $_POST['battsman'];
    $matchId = $_POST['match_id'];

    // Fetch batting records for the selected batsman
    $teamRecordQuery = "SELECT dot_ball, one_run, two_run, three_run, four_run, six_run FROM tbl_team_record WHERE id = ? AND match_id = ?";
    $stmt = $conn->prepare($teamRecordQuery);
    $stmt->bind_param("ii", $battsmanId, $matchId);
    $stmt->execute();
    $recordResult = $stmt->get_result();
    $battsmanData = $recordResult->fetch_assoc();

    // Calculate total runs scored by the batsman in batting
    if ($battsmanData) {
        // Total runs scored while batting
        $totalBattingRuns = $battsmanData['one_run'] + 
                            ($battsmanData['two_run'] * 2) + 
                            ($battsmanData['three_run'] * 3) + 
                            ($battsmanData['four_run'] * 4) + 
                            ($battsmanData['six_run'] * 6);

        // Calculate total runs scored while fielding (assuming this is recorded elsewhere)
        // You can adjust this if you have a specific way to calculate fielding runs
        $totalFieldingRuns = $battsmanData['dot_ball']; // Example calculation, replace with actual logic if available

        echo "Total Batting Runs by " . @$battsmanData['id'] . ": " . $totalBattingRuns . "<br>";
        echo "Total Fielding Runs for " . @$battsmanData['id'] . ": " . $totalFieldingRuns . "<br>";
    } else {
        echo "No records found for the selected batsman.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Batsman Record</title>
</head>
<body>
    <form method="post" action="">
        <label for="battsman">Select Batsman:</label>
        <select name="battsman" id="battsman" class="form-control my-2 mt-2" required>
            <?php while ($row = $battsmanResult->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
        </select>

        <label for="match">Select Match ID:</label>
        <select name="match_id" id="match" class="form-control mt-2 my-2" required>
            <?php
            // Fetch match IDs for dropdown
            $matchQuery = "SELECT id FROM tbl_matches"; // Adjust table name as necessary
            $matchResult = $conn->query($matchQuery);
            while ($row = $matchResult->fetch_assoc()) {
            ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['id']; ?></option>
            <?php } ?>
        </select>

        <input type="submit" class="btn btn-primary mt-1" value="Submit">
    </form>
</body>
</html>

<?php
$conn->close();
?>
<?php include 'footer.php'; ?>