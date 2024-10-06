<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cric_stats";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch players for dropdown
$playersQuery = "SELECT id, name FROM tbl_player";
$playersResult = $conn->query($playersQuery);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedPlayer = @$_POST['id'];
    
    // Get the team of the selected player
    $teamQuery = "SELECT player_team1, player_team2 FROM tbl_teamselection WHERE id = ?";
    $stmt = $conn->prepare($teamQuery);
    $stmt->bind_param("i", $selectedPlayer);
    $stmt->execute();
    $teamResult = $stmt->get_result();
    
    if ($teamRow = $teamResult->fetch_assoc()) {
        $playerTeam1 = $teamRow['player_team1'];
        $playerTeam2 = $teamRow['player_team2'];
        
        // Fetch performance against teams
        $performanceQuery = "
            SELECT team_1,team_2, 
                   SUM(dot_ball) AS total_dot, 
                   SUM(one_run) AS total_one, 
                   SUM(two_run) AS total_two, 
                   SUM(three_run) AS total_three, 
                   SUM(four_run) AS total_four, 
                   SUM(six) AS total_six,
                   SUM(dot_ball + one_run + two_run + three_run + four_run + six) AS total_runs
            FROM tbl_team_record 
            WHERE (battsman = ? OR bowler= ?) AND (team_1 = ? OR team_2 = ?)
            GROUP BY team_1,team_2
        ";
        
        $stmt = $conn->prepare($performanceQuery);
        $stmt->bind_param("iiss", $selectedPlayer, $selectedPlayer, $playerTeam1, $playerTeam2);
        $stmt->execute();
        $performanceResult = $stmt->get_result();
        
        // Display performance
        echo "<h3>Performance of Player ID: $selectedPlayer</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Team</th><th>Total Dot Balls</th><th>Total One Runs</th><th>Total Two Runs</th><th>Total Three Runs</th><th>Total Four Runs</th><th>Total Sixes</th><th>Total Runs</th></tr>";
        
        while ($row = $performanceResult->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['team_name']}</td>
                    <td>{$row['total_dot']}</td>
                    <td>{$row['total_one']}</td>
                    <td>{$row['total_two']}</td>
                    <td>{$row['total_three']}</td>
                    <td>{$row['total_four']}</td>
                    <td>{$row['total_six']}</td>
                    <td>{$row['total_runs']}</td>
                  </tr>";
        }
        
        echo "</table>";
    } else {
        echo "Player not found in team selection.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Player Performance</title>
</head>
<body>
    <h2>Select a Player</h2>
    <form method="POST" action="">
        <select name="id">
            <?php
            if ($playersResult->num_rows > 0) {
                while ($row = $playersResult->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                }
            } else {
                echo "<option value=''>No players found</option>";
            }
            ?>
        </select>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
