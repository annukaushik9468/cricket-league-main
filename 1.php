<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'cric_stats');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch players for the dropdown
$player_query = "SELECT id, name FROM tbl_player";
$players_result = $conn->query($player_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Performance</title>
</head>
<body>
    <form method="POST">
        <label for="player">Select Player:</label>
        <select name="id" id="player" required>
            <option value="">Select a player</option>
            <?php while ($row = $players_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
        </select>
        <input type="submit" value="Fetch Performance">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];

        // Fetch player team from tbl_teamselection
        $team_query = "SELECT player_team1, player_team2 FROM tbl_teamselection WHERE id = ?";
        $stmt = $conn->prepare($team_query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $team_result = $stmt->get_result();
        $team_row = $team_result->fetch_assoc();

        // Determine which teams the player belongs to
        $team_1 = $team_row['player_team1'];
        $team_2 = $team_row['player_team2'];

        // Fetch performance data from tbl_team_record against both teams
        $performance_query = "SELECT * FROM tbl_team_record WHERE id = ? AND (team_1 = ? OR team_2 = ?)";
        $stmt = $conn->prepare($performance_query);
        $stmt->bind_param("iss", $id, $team_1, $team_2);
        $stmt->execute();
        $performance_result = $stmt->get_result();

        // Fetch performance data
        if ($performance_row = $performance_result->fetch_assoc()) {
            // Calculate total runs
            $total_runs = $performance_row['dot_ball'] * 0 + 
                          $performance_row['one_run'] * 1 + 
                          $performance_row['two_run'] * 2 + 
                          $performance_row['three_run'] * 3 + 
                          $performance_row['four_run'] * 4 + 
                          $performance_row['six_run'] * 6;

            // Determine opposing team name
            $opposing_team = ($performance_row['team_1'] == $team_1) ? $performance_row['team_2'] : $performance_row['team_1'];

            echo "<h3>Performance of " . $performance_row['player_name'] . " against " . $opposing_team . ":</h3>";
            echo "<p>Total Runs: " . $total_runs . "</p>";
            echo "<p>Dot Balls: " . $performance_row['dot_ball'] . "</p>";
            echo "<p>1 Run: " . $performance_row['one_run'] . "</p>";
            echo "<p>2 Runs: " . $performance_row['two_run'] . "</p>";
            echo "<p>3 Runs: " . $performance_row['three_run'] . "</p>";
            echo "<p>4 Runs: " . $performance_row['four_run'] . "</p>";
            echo "<p>6 Runs: " . $performance_row['six_run'] . "</p>";
        } else {
            echo "<p>No performance data found for this player against the selected teams.</p>";
        }
    }

    // Close the connection
    $conn->close();
    ?>
</body>
</html>
