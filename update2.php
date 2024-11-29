<?php 
ob_start(); // Start output buffering
include 'dbconnection.php';
include 'header.php';

if (isset($_POST['update_page'])) {
    if (count($_POST) > 0) {
        // Update query
        $sql = "UPDATE tbl_state 
                SET country_id = '" . $_POST['country_id'] . "', 
                    title = '" . $_POST['title'] . "' 
                WHERE id = '" . $_GET['id'] . "'";
        
        if (mysqli_query($con, $sql)) {
            // Redirect to tbl_statelisting.php after successful update
            header('Location: tbl_statelisting.php');
            exit(); // Always call exit after header to stop further execution
        }

        $message = "<p style='color:green;'>Record modified successfully!</p>";
    }
}

// Fetch the record to be edited
$result = mysqli_query($con, "SELECT * FROM tbl_state WHERE id = '" . $_GET['id'] . "'");
$row = mysqli_fetch_array($result);
?>

<!-- Update Form -->
<form action="" method="POST" enctype="multipart/form-data">
    <div class="col-sm-8">
    <select class="col-md-12 mt-1 mb-3 form-control" aria-label="Default select example" name="state_id">
        <option disabled selected>Select Country</option>
        <?php
        $title = mysqli_query($con, "SELECT * FROM tbl_country");
        while ($t = mysqli_fetch_assoc($title)) {
            // Check if the state is selected
            $selected = ($row['country_id'] == $t['id']) ? 'selected' : '';
        ?>
            <option value="<?php echo $t['id']; ?>" <?php echo $selected; ?>>
                <?php echo $t['title']; ?>
            </option>
        <?php } ?>
    </select>
</div>

    <div class="col-md-12">
        <label for="exampleInputPassword1">Title</label>
        <input type="text" class="form-control" name="title" value="<?php echo $row['title']; ?>" required>
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
