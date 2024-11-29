<?php 
ob_start(); // Start output buffering
include 'dbconnection.php';
include 'header.php';

if (isset($_POST['update_page'])) {
    if (count($_POST) > 0) {
        // Update query
        $sql = "UPDATE tbl_teamselection 
                SET series_id = '" . $_POST['series_id'] . "', 
                    season_id = '" . $_POST['season_id'] . "', 
                    match_no = '" . $_POST['match_no'] . "', 
                    team_1 = '" . $_POST['team_1'] . "', 
                    team_2 = '" . $_POST['team_2'] . "', 
                    player_team1 = '" . $_POST['player_team1'] . "', 
                    player_team2 = '" . $_POST['player_team2'] . "' 
                WHERE id = '" . $_GET['id'] . "'";

        if (mysqli_query($con, $sql)) {
            // Redirect to tbl_teamselection_listing.php after successful update
            header('Location: tbl_teamselection_listing.php');
            exit(); // Stop further script execution
        }

        $message = "<p style='color:green;'>Record modified successfully!</p>";
    }
}

// Fetch the record to be edited
$result = mysqli_query($con, "SELECT * FROM tbl_teamselection WHERE id = '" . $_GET['id'] . "'");
$row = mysqli_fetch_array($result);
?>

<!-- Update Form -->
<form action="" method="POST" enctype="multipart/form-data">
     <div class="col-sm-8">
    <select class="col-md-12 mt-1 mb-3 form-control" aria-label="Default select example" name="series_id">
        <option disabled selected>Select Series</option>
        <?php
        $title = mysqli_query($con, "SELECT * FROM tbl_series");
        while ($t = mysqli_fetch_assoc($title)) {
            // Check if the state is selected
            $selected = ($row['series_id'] == $t['id']) ? 'selected' : '';
        ?>
            <option value="<?php echo $t['id']; ?>" <?php echo $selected; ?>>
                <?php echo $t['title']; ?>
            </option>
        <?php } ?>
    </select>
</div>

    <div class="col-sm-8">
    <select class="col-md-12 mt-1 mb-3 form-control" aria-label="Default select example" name="season_id">
        <option disabled selected>Select State</option>
        <?php
        $title = mysqli_query($con, "SELECT * FROM tbl_season");
        while ($t = mysqli_fetch_assoc($title)) {
            // Check if the state is selected
            $selected = ($row['season_id'] == $t['id']) ? 'selected' : '';
        ?>
            <option value="<?php echo $t['id']; ?>" <?php echo $selected; ?>>
                <?php echo $t['title']; ?>
            </option>
        <?php } ?>
    </select>
</div>
    <div class="col-md-12">
        <label for="exampleInputPassword1">Match No</label>
        <input type="text" class="form-control" name="match_no" value="<?php echo $row['match_no']; ?>" required>
    </div>

    <div class="col-sm-8">
    <select class="col-md-12 mt-1 mb-3 form-control" aria-label="Default select example" name="team_1">
        <option disabled selected>Select Team 1</option>
        <?php
        $title = mysqli_query($con, "SELECT * FROM tbl_team");
        while ($t = mysqli_fetch_assoc($title)) {
            // Check if the state is selected
            $selected = ($row['team_1'] == $t['id']) ? 'selected' : '';
        ?>
            <option value="<?php echo $t['id']; ?>" <?php echo $selected; ?>>
                <?php echo $t['title']; ?>
            </option>
        <?php } ?>
    </select>
</div>
    <div class="col-sm-8">
    <select class="col-md-12 mt-1 mb-3 form-control" aria-label="Default select example" name="team_2">
        <option disabled selected>Select Team 2</option>
        <?php
        $title = mysqli_query($con, "SELECT * FROM tbl_team");
        while ($t = mysqli_fetch_assoc($title)) {
            // Check if the state is selected
            $selected = ($row['team_2'] == $t['id']) ? 'selected' : '';
        ?>
            <option value="<?php echo $t['id']; ?>" <?php echo $selected; ?>>
                <?php echo $t['title']; ?>
            </option>
        <?php } ?>
    </select>
</div>


      <div class="col-sm-8">
    <select class="col-md-12 mt-1 mb-3 form-control" aria-label="Default select example" name="player_team1">
        <option disabled selected>Select Player 1</option>
        <?php
        $title = mysqli_query($con, "SELECT * FROM tbl_player");
        while ($t = mysqli_fetch_assoc($title)) {
            // Check if the state is selected
            $selected = ($row['player_team1'] == $t['id']) ? 'selected' : '';
        ?>
            <option value="<?php echo $t['id']; ?>" <?php echo $selected; ?>>
                <?php echo $t['name']; ?>
            </option>
        <?php } ?>
    </select>
</div>

        <div class="col-sm-8">
    <select class="col-md-12 mt-1 mb-3 form-control" aria-label="Default select example" name="player_team2">
        <option disabled selected>Select Player 1</option>
        <?php
        $title = mysqli_query($con, "SELECT * FROM tbl_player");
        while ($t = mysqli_fetch_assoc($title)) {
            // Check if the state is selected
            $selected = ($row['player_team2'] == $t['id']) ? 'selected' : '';
        ?>
            <option value="<?php echo $t['id']; ?>" <?php echo $selected; ?>>
                <?php echo $t['name']; ?>
            </option>
        <?php } ?>
    </select>
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
