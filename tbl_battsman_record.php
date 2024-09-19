<?php include 'dbconnection.php' ?>

<?php include 'header.php' ?>

<main id="main" class="main">

    <div class="pagetitle">
     
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">


<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cric_stats"; // Change this to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching dropdown data for battsman, season, and series
$batsmen = $conn->query("SELECT DISTINCT battsman FROM tbl_team_record");
$seasons = $conn->query("SELECT DISTINCT season_id FROM tbl_team_record");
$series = $conn->query("SELECT DISTINCT series_id FROM tbl_team_record");

?>

<form method="POST" action="">
    <label for="battsman">Select battsman:</label>
    <select name="battsman" id="battsman" class="form-control">
        <?php while ($row = $batsmen->fetch_assoc()) { ?>
            <option value="<?php echo $row['battsman']; ?>"><?php echo $row['battsman']; ?></option>
        <?php } ?>
    </select>

    <label for="season_id">Select Season:</label>
    <select name="season_id" id="season_id" class="form-control">
        <?php while ($row = $seasons->fetch_assoc()) { ?>
            <option value="<?php echo $row['season_id']; ?>"><?php echo $row['season_id']; ?></option>
        <?php } ?>
    </select>

    <label for="series_id">Select Series:</label>
    <select name="series_id" id="series_id" class="form-control">
        <?php while ($row = $series->fetch_assoc()) { ?>
            <option value="<?php echo $row['series_id']; ?>"><?php echo $row['series_id']; ?></option>
        <?php } ?>
    </select>

    <button type="submit" name="submit" class="btn btn-primary my-3">Calculate Total Runs</button>
</form>

<?php
if (isset($_POST['submit'])) {
    $battsman = $_POST['battsman'];
    $season_id = $_POST['season_id'];
    $series_id = $_POST['series_id'];

    // Fetch the battsman's performance based on season and series
    $query = "SELECT SUM(one_run) AS one_runs, SUM(two_run) AS two_runs, SUM(three_run) AS three_runs,
                     SUM(four_run) AS four_runs, SUM(six_run) AS six_runs
              FROM tbl_team_record 
              WHERE battsman = '$battsman' AND season_id = '$season_id' AND series_id = '$series_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $total_runs = ($data['one_runs'] * 1) + ($data['two_runs'] * 2) + ($data['three_runs'] * 3) + ($data['four_runs'] * 4) + ($data['six_runs'] * 6);

        echo "<h3>Total Runs for $battsman in Season $season_id and Series $series_id: $total_runs</h3>";
    } else {
        echo "<h3>No data available for the selected battsman, season, or series.</h3>";
    }
}

$conn->close();
?>
</div>
    </section>

  </main><!-- End #main -->
</body>


<?php include 'footer.php' ?>