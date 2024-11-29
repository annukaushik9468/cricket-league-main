<?php 
ob_start(); // Start output buffering
include 'dbconnection.php';
include 'header.php';

// Check if the form is submitted
if (isset($_POST['update_page'])) {
    if (count($_POST) > 0) {
        // Update query
        $sql = "UPDATE tbl_country 
                SET title = '" . $_POST['title'] . "', 
                    slug = '" . $_POST['slug'] . "' 
                WHERE id = '" . $_GET['id'] . "'";
        
        if (mysqli_query($con, $sql)) {
            // Redirect to tbl_country.php after successful update
            header('Location: tbl_countrylisting.php');
            exit();
        } else {
            $message = "<p style='color:red;'>Error updating record: " . mysqli_error($con) . "</p>";
        }
    }
}

// Fetch the record to be edited
$result = mysqli_query($con, "SELECT * FROM tbl_country WHERE id = '" . $_GET['id'] . "'");
$row = mysqli_fetch_array($result);
?>

<!-- Update Form -->
<form action="" method="POST" enctype="multipart/form-data">
    <div class="col-md-12">
        <label for="exampleInputPassword1">Title</label>
        <input type="text" class="form-control" name="title" value="<?php echo $row['title']; ?>" required>
    </div>

    <div class="col-md-12">
        <label for="exampleInputPassword1">Slug</label>
        <input type="text" class="form-control" name="slug" value="<?php echo $row['slug']; ?>" required>
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
