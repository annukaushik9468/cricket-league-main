<?php include 'dbconnection.php' ?>

<?php include 'header.php' ?>

<?php
// Database connection
$host = 'localhost';
$dbname = 'cric_stats';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Handle form submission
if (isset($_POST['submit'])) {
    $season_id = $_POST['season_id'];
    $series_id = $_POST['series_id'];
    $battsman = $_POST['battsman'];
    $bowler = $_POST['bowler'];

    // Fetch data from the database
    $sql = "SELECT dot_ball, one_run, two_run, three_run, four_run, six_run, wide, wicket 
            FROM tbl_team_record 
            WHERE season_id = :season_id 
            AND series_id = :series_id 
            AND battsman = :battsman 
            AND bowler = :bowler";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':season_id' => $season_id,
        ':series_id' => $series_id,
        ':battsman' => $battsman,
        ':bowler' => $bowler,
    ]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        // Calculate total runs
        $total_runs = $data['one_run'] + ($data['two_run'] * 2) + ($data['three_run'] * 3) + ($data['four_run'] * 4) + ($data['six_run'] * 6);

        // Display results
        echo "<h3>Total Runs</h3>";
        echo "Total Runs: $total_runs<br>";
    } else {
        echo "No data found for the selected criteria.";
    }
}
?>

<main id="main" class="main">

    <div class="pagetitle">
     
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

    <form method="post" action="">
        <label for="season_id">Select Season:</label>
        <select name="season_id" id="season_id" class="form-control">
            <?php
            // Populate season_id dropdown
            $sql = "SELECT DISTINCT season_id FROM tbl_team_record";
            $stmt = $pdo->query($sql);
            $seasons = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($seasons as $season) {
                echo "<option value=\"$season\">$season</option>";
            }
            ?>
        </select>
        
        <label for="series_id">Select Series:</label>
        <select name="series_id" id="series_id" class="form-control">
            <?php
            // Populate series_id dropdown
            $sql = "SELECT DISTINCT series_id FROM tbl_team_record";
            $stmt = $pdo->query($sql);
            $series = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($series as $ser) {
                echo "<option value=\"$ser\">$ser</option>";
            }
            ?>
        </select>
        
        <label for="battsman">Select Batsman:</label>
        <select name="battsman" id="battsman" class="form-control">
            <?php
            // Populate batsman dropdown
            $sql = "SELECT DISTINCT battsman FROM tbl_team_record";
            $stmt = $pdo->query($sql);
            $batsmen = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($batsmen as $bat) {
                echo "<option value=\"$bat\">$bat</option>";
            }
            ?>
        </select>
        
        <label for="bowler">Select Bowler:</label>
        <select name="bowler" id="bowler" class="form-control">
            <?php
            // Populate bowler dropdown
            $sql = "SELECT DISTINCT bowler FROM tbl_team_record";
            $stmt = $pdo->query($sql);
            $bowlers = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($bowlers as $bowl) {
                echo "<option value=\"$bowl\">$bowl</option>";
            }
            ?>
        </select>
        
        <input type="submit" name="submit" class="btn btn-primary my-3" value="Fetch Data">
    </form>

 </div>
    </section>

  </main><!-- End #main -->
</body>


<?php include 'footer.php' ?>