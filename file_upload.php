<?php
include 'dbconnection.php';
include 'header.php';
?>

  <main id="main" class="main">

    <div class="pagetitle">
     
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
    <?php
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'cric_states');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch data for dropdowns
    $seasons = $conn->query("SELECT id, title FROM tbl_season");
    $series = $conn->query("SELECT id, title FROM tbl_series");
    $teams = $conn->query("SELECT id, title FROM tbl_team");
    $team = $conn->query("SELECT id, title FROM tbl_team");
    ?>
    
    <form action="" method="post" enctype="multipart/form-data">
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

    <?php
    $conn->close();
    ?>
</div>
    </section>

  </main><!-- End #main -->

  <?php
if(isset($_POST['submit'])) {
    $season_id = $_POST['season_id'];
    $series_id = $_POST['series_id'];
    $team_1 = $_POST['team_1'];
    $team_2 = $_POST['team_2'];
    $file = $_FILES['file']['tmp_name'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'cric_states');
    if($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if(($handle = fopen($file, 'r')) !== FALSE) {
        while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            // Assuming CSV columns are (column1, column2, column3, ...)
            $match_no = $data[0];
             $bowler_name = $data[1];
            $battsman_name = $data[2];
             $dot_ball = $data[3];
              $total_one_run = $data[4];
               $total_two_run = $data[5];
               $total_three_run = $data[6];
               $total_four_run = $data[7];
               $total_six_run = $data[8];
               $wide = $data[9];

            // Add more columns as needed

            // Insert into the database
            $sql = "INSERT INTO tbl_team_player (season_id, series_id, team_1, team_2, match_no, bowler_name, battsman_name, dot_ball, total_one_run, total_two_run, total_three_run, total_four_run, total_six_run, wide) VALUES ('$season_id', '$series_id', '$team_1', '$team_2','$match_no','$bowler_name', '$battsman_name', '$dot_ball', '$total_one_run', '$total_two_run','$total_three_run', '$total_four_run', '$total_six_run','$wide')";
            if(!$conn->query($sql)) {
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