<?php 
ob_start(); // Start output buffering to prevent header errors
include 'dbconnection.php';
include 'header.php';

if (isset($_POST['update_page'])) {
    if (count($_POST) > 0) {
        // Prepare the UPDATE query
        $sql = "UPDATE tbl_teamplayer 
                SET series_id = '" . $_POST['series_id'] . "', 
                    season_id = '" . $_POST['season_id'] . "', 
                    team_id = '" . $_POST['team_id'] . "', 
                    player_name = '" . $_POST['player_name'] . "', 
                    upload_path = '" . $_POST['upload_path'] . "', 
                    position = '" . $_POST['position'] . "' 
                WHERE id = '" . $_GET['id'] . "'";

        // Execute the query
        if (mysqli_query($con, $sql)) {
            // Redirect to the listing page after successful update
            header('Location: tbl_teamplayer_listing.php');
            exit(); // Stop further script execution
        }

        $message = "<p style='color:green;'>Record modified successfully!</p>";
    }
}

// Fetch the record to be updated
$result = mysqli_query($con, "SELECT * FROM tbl_teamplayer WHERE id = '" . $_GET['id'] . "'");
$row = mysqli_fetch_array($result);
?>

<!-- Update Form -->
<form action="" method="POST" enctype="multipart/form-data">
    <div class="col-md-12">
        <label for="exampleInputPassword1">Series Id</label>
        <input type="text" class="form-control" name="series_id" value="<?php echo $row['series_id']; ?>" required>
    </div>

    <div class="col-md-12">
        <label for="exampleInputPassword1">Season Id</label>
        <input type="text" class="form-control" name="season_id" value="<?php echo $row['season_id']; ?>" required>
    </div>

    <div class="col-md-12">
        <label for="exampleInputPassword1">Team ID</label>
        <input type="text" class="form-control" name="team_id" value="<?php echo $row['team_id']; ?>" required>
    </div>

    <div class="col-md-12">
        <label for="exampleInputPassword1">Player Name</label>
        <input type="text" class="form-control" name="player_name" value="<?php echo $row['player_name']; ?>" required>
    </div>

    <div class="col-md-12">
        <label for="exampleInputPassword1">Upload Path</label>
        <input type="text" class="form-control" name="upload_path" value="<?php echo $row['upload_path']; ?>" required>
    </div>

    <div class="col-md-12">
        <label for="exampleInputPassword1">Position</label>
        <input type="text" class="form-control" name="position" value="<?php echo $row['position']; ?>" required>
    </div>

    <button type="submit" name="update_page" class="btn btn-primary mt-4 my-8" style="height:50px;">Update</button>
</form> 

<?php
if (isset($message)) {
    echo $message;
}

include 'footer.php';
ob_end_flush(); // End output buffering
?>
