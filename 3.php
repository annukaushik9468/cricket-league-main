<?php
include 'dbconnection.php';
include 'header.php';

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $season_id = mysqli_real_escape_string($con, $_POST['season_id']);
    $series_id = mysqli_real_escape_string($con, $_POST['series_id']);
    $team_1 = mysqli_real_escape_string($con, $_POST['team_1']);
    $team_2 = mysqli_real_escape_string($con, $_POST['team_2']);
    $match_no = mysqli_real_escape_string($con, $_POST['match_no']);
    $player_ids = $_POST['player_id'];

    // Fetch IDs for team_1 and team_2 from tbl_team
    $team_id_query = "SELECT id FROM tbl_team WHERE team_name = '$team_1' OR team_name = '$team_2'";
    $team_id_result = mysqli_query($con, $team_id_query);
    if (!$team_id_result) {
        die('Error executing query: ' . mysqli_error($con));
    }

    $team_ids = [];
    while ($row = mysqli_fetch_assoc($team_id_result)) {
        $team_ids[] = $row['id'];
    }

    // Ensure we have two teams' IDs
    if (count($team_ids) < 2) {
        die('Unable to find IDs for both teams.');
    }

    $batting_team_id = $team_ids[0]; // Assuming team_1 is the batting team
    $toss_winner_id = $team_ids[1]; // Assuming team_2 is the toss winner

    // Handle file upload
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['csv_file']['tmp_name'];
        $file_name = $_FILES['csv_file']['name'];
        $upload_path = 'uploads/' . basename($file_name);
        if (!move_uploaded_file($file_tmp_name, $upload_path)) {
            die('Error uploading file');
        }
    } else {
        $upload_path = ''; // Handle the case where file upload fails
    }

    // Insert data into tbl_match_data
    foreach ($player_ids as $player_id) {
        $insertquery = "INSERT INTO tbl_match_data (season_id, series_id, team_1, team_2, match_no, upload_path, batting_team, toss_winner, player_name) VALUES ('$season_id', '$series_id', '$team_1', '$team_2', '$match_no', '$upload_path', '$batting_team_id', '$toss_winner_id', '$player_id')";
        $iquery = mysqli_query($con, $insertquery);
        if (!$iquery) {
            die('Error inserting data: ' . mysqli_error($con));
        }
    }

    if ($iquery) {
        ?>
        <script>
            alert("Data inserted successfully");
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Data insertion failed");
        </script>
        <?php
    }
}
?>

<section class="section dashboard">
    <div class="row">
        <div class="container my-3">
            <form action="" method="POST" id="check" autocomplete="off" enctype="multipart/form-data">
                <div class="container">
                    <h2 class="text-center">Data</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Series Id</th>
                                <th>Season</th>
                                <th>Team 1</th>
                                <th>Team 2</th>
                                <th>Match No</th>
                                <th>Player Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select class="col-md-12 mt-3 mb-3 form-control" name="series_id">
                                        <option selected>Series Id</option>
                                        <?php
                                        $series_query = mysqli_query($con, "SELECT * FROM tbl_schedule");
                                        if (!$series_query) {
                                            die('Error executing query: ' . mysqli_error($con));
                                        }
                                        while ($s = mysqli_fetch_assoc($series_query)) {
                                            ?>
                                            <option value="<?php echo $s['series_id'] ?>"><?php echo $s['series_id'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="col-md-8 form-control" name="season_id">
                                        <option selected>Season</option>
                                        <?php
                                        $season_query = mysqli_query($con, "SELECT * FROM tbl_schedule");
                                        if (!$season_query) {
                                            die('Error executing query: ' . mysqli_error($con));
                                        }
                                        while ($s = mysqli_fetch_assoc($season_query)) {
                                            ?>
                                            <option value="<?php echo $s['season_id'] ?>"><?php echo $s['season_id'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="col-md-8 form-control" name="team_1">
                                        <option selected>Team 1</option>
                                        <?php
                                        $team_query = mysqli_query($con, "SELECT DISTINCT team_name FROM tbl_team");
                                        if (!$team_query) {
                                            die('Error executing query: ' . mysqli_error($con));
                                        }
                                        while ($t = mysqli_fetch_assoc($team_query)) {
                                            ?>
                                            <option value="<?php echo $t['team_name'] ?>"><?php echo $t['team_name'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="col-md-8 form-control" name="team_2">
                                        <option selected>Team 2</option>
                                        <?php
                                        $team_query = mysqli_query($con, "SELECT DISTINCT team_name FROM tbl_team");
                                        if (!$team_query) {
                                            die('Error executing query: ' . mysqli_error($con));
                                        }
                                        while ($t = mysqli_fetch_assoc($team_query)) {
                                            ?>
                                            <option value="<?php echo $t['team_name'] ?>"><?php echo $t['team_name'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="col-md-8 form-control" name="match_no">
                                        <option selected>Match No</option>
                                        <?php
                                        $match_query = mysqli_query($con, "SELECT DISTINCT match_no FROM tbl_schedule");
                                        if (!$match_query) {
                                            die('Error executing query: ' . mysqli_error($con));
                                        }
                                        while ($s = mysqli_fetch_assoc($match_query)) {
                                            ?>
                                            <option value="<?php echo $s['match_no'] ?>"><?php echo $s['match_no'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="file" name="csv_file" class="form-control">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table" id="mytable">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>ID</th>
                                <th>Player Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tbl_player";
                            $result = mysqli_query($con, $sql);
                            if (!$result) {
                                die('Error executing query: ' . mysqli_error($con));
                            }
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>
                                    <td><input type="checkbox" name="player_id[]" value="' . $row["name"] . '"></td>
                                    <td>' . $row["id"] . '</td>
                                    <td>' . $row["name"] . '</td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" name="submit" class="btn btn-primary col-md-2 mt-4">Submit</button>
            </form>
        </div>
    </div>
</section>

<?php
include 'footer.php';
?>
