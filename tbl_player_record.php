<?php
include 'dbconnection.php';
?>

  <?php include 'header.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
     
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">


<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cric_stats";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch unique values for dropdowns
function fetchUniqueValues($conn, $column_name, $table_name) {
    $values = [];
    $sql = "SELECT DISTINCT $column_name FROM $table_name";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $values[] = $row[$column_name];
    }
    return $values;
}

// Fetch dropdown values
$seasons = fetchUniqueValues($conn, 'season_id', 'tbl_team_record');
$series = fetchUniqueValues($conn, 'series_id', 'tbl_team_record');
$matches = fetchUniqueValues($conn, 'match_no', 'tbl_team_record');
$battsmen = fetchUniqueValues($conn, 'battsman', 'tbl_team_record');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    @$season_id = $_POST['season_id'];
    @$series_id = $_POST['series_id'];
    @$match_no = $_POST['match_no'];
    $battsman = $_POST['battsman'];

    // SQL query to fetch and aggregate the data
    $sql = "
        SELECT
            season_id,
            series_id,
            match_no,
            battsman,
            SUM(dot_ball) AS total_dot_balls,
            SUM(one_run) AS total_one_runs,
            SUM(two_run) AS total_two_runs,
            SUM(three_run) AS total_three_runs,
            SUM(four_run) AS total_four_runs,
            SUM(six_run) AS total_six_runs,
            SUM(wide) AS total_wides,
            SUM(wicket) AS total_wickets
        FROM
            tbl_team_record
        WHERE
            season_id = ? AND series_id = ? AND match_no = ? AND battsman = ?
        GROUP BY
            season_id, series_id, match_no, battsman
    ";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $season_id, $series_id, $match_no, $battsman);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch the row
        $row = $result->fetch_assoc();

        // Prepare the insert query for the aggregated data
        $insert_sql = "
            INSERT INTO tbl_player_record (season_id, series_id, match_no, battsman, total_dot_balls, total_one_runs, total_two_runs, total_three_runs, total_four_runs, total_six_runs, total_wides, total_wickets)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        // Prepare the statement
        $stmt = $conn->prepare($insert_sql);

        // Bind the parameters
        $stmt->bind_param("iiisiiiiiiii", $row['season_id'], $row['series_id'], $row['match_no'], $row['battsman'], $row['total_dot_balls'], $row['total_one_runs'], $row['total_two_runs'], $row['total_three_runs'], $row['total_four_runs'], $row['total_six_runs'], $row['total_wides'], $row['total_wickets']);

        // Execute the statement
        if ($stmt->execute() === TRUE) {
            echo "Record inserted successfully<br>";
        } else {
            echo "Error: " . $stmt->error . "<br>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "No records found";
    }
}
?>
    <h2>Select Criteria to Search and Aggregate Data</h2>
    <form method="post" action="">
        <label for="season_id">Season ID:</label>
        <select name="season_id" id="season_id" class="form-control">
            <?php foreach ($seasons as $season) : ?>
                <option value="<?php echo $season; ?>"><?php echo $season; ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="series_id">Series ID:</label>
        <select name="series_id" id="series_id" class="form-control">
            <?php foreach ($series as $serie) : ?>
                <option value="<?php echo $serie; ?>"><?php echo $serie; ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="match_no">Match No:</label>
        <select name="match_no" id="match_no" class="form-control">
            <?php foreach ($matches as $match) : ?>
                <option value="<?php echo $match; ?>"><?php echo $match; ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="battsman">Batsman:</label>
        <select name="battsman" id="battsman" class="form-control">
            <?php foreach ($battsmen as $battsman) : ?>
                <option value="<?php echo $battsman; ?>"><?php echo $battsman; ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="submit" value="Submit" class="btn btn-success">
    </form>


<?php
// Close the connection
$conn->close();
?>

</div>
    </section>

  </main><!-- End #main -->

  <?php include 'footer.php'; ?>

</body>

</html>