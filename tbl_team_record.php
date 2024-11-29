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
            $stadium = $conn->query("SELECT id, title FROM tbl_stadium");

            // Fetch team matches for combined dropdown
            $matches = $conn->query("SELECT 
                                        m.id AS match_id, 
                                        t1.title AS team1, 
                                        t2.title AS team2 
                                      FROM tbl_matches m 
                                      JOIN tbl_team t1 ON m.team_1 = t1.id 
                                      JOIN tbl_team t2 ON m.team_2 = t2.id");
            ?>
            
            <form action="" method="post" enctype="multipart/form-data">
                <!-- Toss Winner Dropdown -->
               
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

                <label for="stadium_id">Stadium ID:</label>
                <select name="stadium_id" id="stadium_id" class="form-control">
                    <?php while($row = $stadium->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="team_match">Teams:</label>
                <select name="team_match" id="team_match" class="form-control">
                    <?php while($row = $matches->fetch_assoc()): ?>
                        <option value="<?php echo $row['match_id']; ?>">
                            <?php echo $row['team1'] . " vs " . $row['team2']; ?>
                        </option>
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
    $stadium_id = $_POST['stadium_id'];
    $team_match = $_POST['team_match']; // Combined match ID
    $file = $_FILES['file']['tmp_name'];

    // Check if a file is uploaded
    if (empty($file) || !file_exists($file)) {
        echo "No file selected or file upload failed.";
        exit;
    }

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'cric_stats');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch team IDs for the selected match
    $match_query = "SELECT team_1, team_2 FROM tbl_matches WHERE id = '$team_match' LIMIT 1";
    $match_result = $conn->query($match_query);
    if ($match_result->num_rows > 0) {
        $match_row = $match_result->fetch_assoc();
        $team1_id = $match_row['team_1'];
        $team2_id = $match_row['team_2'];
    } else {
        echo "Match details not found.";
        exit;
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
                echo "Bowler '$bowler' not found in database.";
                continue;
            }

            // Insert into the database
            $sql = "INSERT INTO tbl_team_record (
                        match_id, season_id, series_id, stadium_id, 
                        match_no, battsman, bowler, dot_ball, one_run, 
                        two_run, three_run, four_run, six_run, wide, 
                        wicket, team_1, team_2
                    ) VALUES (
                        '$team_match', '$season_id', '$series_id', '$stadium_id', 
                        '$match_no', '$battsman', '$bowler a', '$dot_ball', '$one_run', 
                        '$two_run', '$three_run', '$four_run', '$six_run', '$wide', 
                        '$wicket', '$team1_id', '$team2_id'
                    )";

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
