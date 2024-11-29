<?php 
ob_start(); // Start output buffering
include 'dbconnection.php';
include 'header.php';

// Check if the form is submitted
if (isset($_POST['update_page'])) {
    if (count($_POST) > 0) {
        // File upload logic
        $imagePath = $row['images']; // Default to existing image
        if (!empty($_FILES['pic']['name'])) {
            $imagePath = 'uploads/' . basename($_FILES['pic']['name']);
            move_uploaded_file($_FILES['pic']['tmp_name'], $imagePath);
        }

        // Update query
        $sql = "UPDATE tbl_player 
                SET state_id = '" . $_POST['state_id'] . "', 
                    name = '" . $_POST['name'] . "', 
                    slug = '" . $_POST['slug'] . "', 
                    dob = '" . $_POST['dob'] . "', 
                    images = '" . $imagePath . "' 
                WHERE id = '" . $_GET['id'] . "'";

        if (mysqli_query($con, $sql)) {
            // Redirect to tbl_player.php after successful update
            header('Location: tbl_playerlisting.php');
            exit();
        }

        $message = "<p style='color:green;'>Record modified successfully!</p>";
    }
}

// Fetch the record to be edited
$result = mysqli_query($con, "SELECT * FROM tbl_player WHERE id = '" . $_GET['id'] . "'");
$row = mysqli_fetch_array($result);
?>

<!-- Update Form -->
<form action="" method="POST" enctype="multipart/form-data">
     <div class="col-sm-8">
    <select class="col-md-12 mt-1 mb-3 form-control" aria-label="Default select example" name="state_id">
        <option disabled selected>Select State</option>
        <?php
        $title = mysqli_query($con, "SELECT * FROM tbl_state");
        while ($t = mysqli_fetch_assoc($title)) {
            // Check if the state is selected
            $selected = ($row['state_id'] == $t['id']) ? 'selected' : '';
        ?>
            <option value="<?php echo $t['id']; ?>" <?php echo $selected; ?>>
                <?php echo $t['title']; ?>
            </option>
        <?php } ?>
    </select>
</div>


    <div class="col-md-12">
        <label for="exampleInputPassword1">Player Name</label>
        <input type="text" class="form-control" name="name" value="<?php echo @$row['name']; ?>" required>
    </div>

    <div class="col-md-12">
        <label for="exampleInputPassword1">Slug</label>
        <input type="text" class="form-control" name="slug" value="<?php echo @$row['slug']; ?>" required>
    </div>

    <div class="col-md-12">
        <label for="exampleInputPassword1">Date Of Birth</label>
        <input type="date" class="form-control" name="dob" value="<?php echo @$row['dob']; ?>" required>
    </div>

    <div class="form-group col-md-12 mt-4">
        <label for="Image">Upload photo</label>
        <input type="file" name="pic" id="pic">
        <?php if (!empty($row['images'])): ?>
            
        <?php endif; ?>
    </div>

    <button type="submit" name="update_page" class="btn btn-primary mt-4 my-8" style="height:50px;">Update</button>
</form> 

<?php
include 'footer.php';
ob_end_flush(); // End output buffering
?>
