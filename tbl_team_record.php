<?php
include 'dbconnection.php';
include 'header.php';
?>

<main id="main" class="main">
    <div class="pagetitle"></div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <?php
            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'cric_stats');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch data for dropdowns
             $toss = $conn->query("SELECT id, toss_winner FROM tbl_matches");
            $seasons = $conn->query("SELECT id, title FROM tbl_season");
            $series = $conn->query("SELECT id, title FROM tbl_series");
            $teams = $conn->query("SELECT id, title FROM tbl_team");
            $team = $conn->query("SELECT id, title FROM tbl_team");
            ?>
            
            <form action="" method="post" enctype="multipart/form-data">
                    <!-- Toss Winner Dropdown -->
                <label for="match_id">Toss Winner:</label>
                <select name="match_id" id="match_id" class="form-control">
                    <?php while($row = $toss->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['id']; ?></option>
                    <?php endwhile; ?>
                </select><br>
                <label for="season_id">Season ID:</label>
                <select name="season_id" id="season_id" class="form-control">
                    <?php while($row = $seasons->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="series_id">Series ID:</label>
                <select name="series_id" id="series_id" class="form-control">
                    <?php while($row = $series->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="team_1">Team 1:</label>
                <select name="team_1" id="team_1" class="form-control">
                    <?php while($row = $teams->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="team_2">Team 2:</label>
                <select name="team_2" id="team_2" class="form-control">
                    <?php while($row = $team->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                    <?php endwhile; ?>
                </select><br>

            

                <label for="file">CSV File:</label>
                <input type="file" name="file" id="file"><br>

                <input type="submit" name="submit" class="my-2 btn btn-danger" value="Insert Data">
            </form>

            <?php $conn->close(); ?>
        </div>
    </section>
</main><!-- End #main -->

<?php
if (isset($_POST['submit'])) {
    $season_id = $_POST['season_id'];
    $series_id = $_POST['series_id'];
    $team_1 = $_POST['team_1'];
    $team_2 = $_POST['team_2'];
    $match_id = $_POST['match_id']; // Get toss winner from dropdown
    $file = $_FILES['file']['tmp_name'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'cric_stats');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (($handle = fopen($file, 'r')) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            // Assuming CSV columns are (column1, column2, column3, ...)
            $match_no = $data[0];
            $battsman = $data[1];
            $bowler = $data[2];
            $dot_ball = $data[3];
            $one_run = $data[4];
            $two_run = $data[5];
            $three_run = $data[6];
            $four_run = $data[7];
            $six_run = $data[8];
            $wide = $data[9];
            $wicket = $data[10];

            // Fetch batsman ID from tbl_players
            $batsman_query = "SELECT id FROM tbl_player WHERE name = '$battsman' LIMIT 1";
            $batsman_result = $conn->query($batsman_query);
            if ($batsman_result->num_rows > 0) {
                $batsman_row = $batsman_result->fetch_assoc();
                $batsman_id = $batsman_row['id'];
            } else {
                // Handle error if batsman not found, insert failed
                echo "Batsman '$battsman' not found in database.";
                continue;
            }

            // Fetch bowler ID from tbl_players
            $bowler_query = "SELECT id FROM tbl_player WHERE name = '$bowler' LIMIT 1";
            $bowler_result = $conn->query($bowler_query);
            if ($bowler_result->num_rows > 0) {
                $bowler_row = $bowler_result->fetch_assoc();
                $bowler_id = $bowler_row['id'];
            } else {
                // Handle error if bowler not found, insert failed
                echo "Bowler '$bowler' not found in database.";
                continue;
            }

            // Insert into the database using batsman_id and bowler_id
            $sql = "INSERT INTO tbl_team_record (match_id, season_id, series_id, team_1, team_2, match_no, battsman, bowler, dot_ball, one_run, two_run, three_run, four_run, six_run, wide, wicket) 
                    VALUES ('$match_id', '$season_id', '$series_id', '$team_1', '$team_2', '$match_no', '$batsman_id', '$bowler_id', '$dot_ball', '$one_run', '$two_run', '$three_run', '$four_run', '$six_run', '$wide', '$wicket')";

            if (!$conn->query($sql)) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        fclose($handle);
        echo "Data inserted successfully";
    } else {
        echo "Error opening the file.";
    }

    $conn->close();
}

?>

<?php
include 'footer.php';
?>
