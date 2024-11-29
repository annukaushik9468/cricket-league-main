<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "cric_stats");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch players for dropdown
$players = $conn->query("SELECT id, name FROM tbl_player");

// Fetch series for dropdown
$series = $conn->query("SELECT id, title FROM tbl_series");

?>

<form method="POST" action="">
    <label for="player">Select Player:</label>
    <select name="player_id" id="player">
        <?php while ($row = $players->fetch_assoc()) { ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php } ?>
    </select>

    <label for="series">Select Series:</label>
    <select name="series_id" id="series">
        <?php while ($row = $series->fetch_assoc()) { ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
        <?php } ?>
    </select>

    <button type="submit" name="submit">Submit</button>
</form>

<?php
if (isset($_POST['submit'])) {
    $player_id = $_POST['player_id'];
    $series_id = $_POST['series_id'];

    // Fetch match data, bowler faced, and run details in the selected series
    $query = "
        SELECT tr.match_no, bp.bowler, tr.dot_ball, tr.one_run, tr.two_run, tr.three_run, tr.four_run, tr.six_run
        FROM tbl_team_record tr 
        JOIN tbl_player p ON tr.id = p.id 
        JOIN tbl_team_record bp ON tr.bowler = bp.id 
        WHERE tr.id = ? AND tr.series_id = ?
    ";

    // Prepare the query and check for errors
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $player_id, $series_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Match Number</th>
                        <th>Bowler</th>
                        <th>Dot Balls</th>
                        <th>1 Run</th>
                        <th>2 Runs</th>
                        <th>3 Runs</th>
                        <th>4 Runs</th>
                        <th>6 Runs</th>
                        <th>Total Runs</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                $total_runs = $row['one_run'] + ($row['two_run'] * 2) + ($row['three_run'] * 3) + ($row['four_run'] * 4) + ($row['six_run'] * 6);
                echo "<tr>
                        <td>" . $row['match_no'] . "</td>
                        <td>" . $row['bowler'] . "</td>
                        <td>" . $row['dot_ball'] . "</td>
                        <td>" . $row['one_run'] . "</td>
                        <td>" . $row['two_run'] . "</td>
                        <td>" . $row['three_run'] . "</td>
                        <td>" . $row['four_run'] . "</td>
                        <td>" . $row['six_run'] . "</td>
                        <td>" . $total_runs . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No records found for the selected player in the chosen series.";
        }
    } else {
        // Print SQL error if query preparation fails
        echo "Error in query preparation: " . $conn->error;
    }
}
?>
