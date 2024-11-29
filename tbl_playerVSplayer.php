<?php
include 'header.php';
// Database connection
$conn = new mysqli("localhost", "root", "", "cric_stats");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch players for the dropdown
$players = $conn->query("SELECT id, name FROM tbl_player");

// Fetch series for the dropdown
$series = $conn->query("SELECT id, title FROM tbl_series");

?>

<title>Player Data Fetch</title>

<style>
/* Basic Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 20px;
}

/* Container */
.container {
    width: 60%;
    margin: 0 auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h3 {
    color: #333;
    margin-bottom: 20px;
}

/* Form */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-size: 16px;
    font-weight: bold;
    color: #555;
}

select {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button[type="submit"] {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 12px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #45a049;
}

/* Results */
.result {
    margin-top: 30px;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.result h3 {
    margin-bottom: 10px;
    color: #333;
}

.result p {
    font-size: 16px;
    line-height: 1.5;
}

.result br {
    margin-bottom: 5px;
}

/* Results Styling for Player Data */
.result p {
    font-size: 18px;
    font-weight: bold;
    color: #4CAF50;
}

.result .data {
    font-size: 16px;
    color: #333;
    padding-left: 20px;
}

.result .data span {
    display: block;
    margin-bottom: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        width: 90%;
    }
}

</style>

<div class="container">
    <form method="POST" action="">
        <label for="player1">Select Player 1:</label>
        <select name="player1" required>
            <option value="">Select Player</option>
            <?php while ($player = $players->fetch_assoc()) { ?>
                <option value="<?= $player['id'] ?>"><?= $player['name'] ?></option>
            <?php } ?>
        </select>

        <label for="player2">Select Player 2:</label>
        <select name="player2" required>
            <option value="">Select Player</option>
            <?php 
            // Reset players query for Player 2 dropdown
            $players->data_seek(0);
            while ($player = $players->fetch_assoc()) { ?>
                <option value="<?= $player['id'] ?>"><?= $player['name'] ?></option>
            <?php } ?>
        </select>

        <label for="series">Select Series:</label>
        <select name="series" required>
            <option value="">Select Series</option>
            <?php while ($ser = $series->fetch_assoc()) { ?>
                <option value="<?= $ser['id'] ?>"><?= $ser['title'] ?></option>
            <?php } ?>
        </select>

        <button type="submit" name="submit">Fetch Data</button>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $player1 = $_POST['player1'];
        $player2 = $_POST['player2'];
        $series = $_POST['series'];

        // Fetch player 1 name
        $player1Name = $conn->query("SELECT name FROM tbl_player WHERE id = '$player1'")->fetch_assoc()['name'];

        // Fetch player 2 name
        $player2Name = $conn->query("SELECT name FROM tbl_player WHERE id = '$player2'")->fetch_assoc()['name'];

        // Fetch the series title
        $seriesTitle = $conn->query("SELECT title FROM tbl_series WHERE id = '$series'")->fetch_assoc()['title'];

        // Fetch player 1 data
        $result1 = $conn->query("SELECT SUM(dot_ball) AS total_dot, SUM(one_run) AS total_one, 
            SUM(two_run) AS total_two, SUM(three_run) AS total_three, SUM(four_run) AS total_four,
            SUM(six_run) AS total_six FROM tbl_team_record WHERE battsman = '$player1' AND series_id = '$series'");

        // Fetch player 2 data
        $result2 = $conn->query("SELECT SUM(dot_ball) AS total_dot, SUM(one_run) AS total_one, 
            SUM(two_run) AS total_two, SUM(three_run) AS total_three, SUM(four_run) AS total_four,
            SUM(six_run) AS total_six FROM tbl_team_record WHERE battsman = '$player2' AND series_id = '$series'");

        // Calculate totals for player 1
        if ($result1->num_rows > 0) {
            $data1 = $result1->fetch_assoc();
            $total_runs1 = ($data1['total_one'] * 1) + ($data1['total_two'] * 2) + 
                           ($data1['total_three'] * 3) + ($data1['total_four'] * 4) + 
                           ($data1['total_six'] * 6);
            $total_balls1 = $data1['total_dot'] + $data1['total_one'] + $data1['total_two'] + 
                            $data1['total_three'] + $data1['total_four'] + $data1['total_six'];
            echo "<div class='result'><h3>Data for $player1Name:</h3>";
            echo "Total Runs: $total_runs1<br>";
            echo "Total Balls: $total_balls1</div>";
        }

        // Calculate totals for player 2
        if ($result2->num_rows > 0) {
            $data2 = $result2->fetch_assoc();
            $total_runs2 = ($data2['total_one'] * 1) + ($data2['total_two'] * 2) + 
                           ($data2['total_three'] * 3) + ($data2['total_four'] * 4) + 
                           ($data2['total_six'] * 6);
            $total_balls2 = $data2['total_dot'] + $data2['total_one'] + $data2['total_two'] + 
                            $data2['total_three'] + $data2['total_four'] + $data2['total_six'];
            echo "<div class='result'><h3>Data for $player2Name:</h3>";
            echo "Total Runs: $total_runs2<br>";
            echo "Total Balls: $total_balls2</div>";
        }

        // Display series title
        echo "<div class='result'><h3>Series Title: $seriesTitle</h3></div>";
    }
    ?>
</div>
</body>
</html>

<?php $conn->close(); ?>
<?php include 'footer.php'; ?>
