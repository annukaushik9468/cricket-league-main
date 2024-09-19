<?php 
include 'dbconnection.php';
include 'header.php';
if (isset($_POST['update_page']))
{
if(count($_POST)>0) {
    $sql = "UPDATE tbl_player SET state_id='" . $_POST['state_id'] . "', name='" . $_POST['name'] . "', slug='" . $_POST['slug'] . "', dob='" . $_POST['dob'] . "', image='" . $_POST['image'] . "'";
  
    $sql .= " WHERE id='" . $_GET['id'] . "'";
   if(mysqli_query($con, $sql))
  {
    // header('location:ma_listing.php');
  }

  $message = "<p style='color:green;'>Record modify succesfully !</p>";
}
}
$result = mysqli_query($con, "SELECT * FROM tbl_player WHERE id='" . $_GET['id'] . "'");
$row= mysqli_fetch_array($result);
// print_r($updatedata)
?>

<form action="" method="POST" enctype="multipart/form-data">
<div class="col-md-12">
<label for="exampleInputPassword1">State Name</label>
<input type="text" class="form-control" name="state_id" value="<?php echo @$row['state_id']; ?>"></input>
</div>

<div class="col-md-12">
<label for="exampleInputPassword1">Player Name</label>
<input type="text" class="form-control" name="name" value="<?php echo @$row['name']; ?>"></input>
</div>

<div class="col-md-12">
<label for="exampleInputPassword1">Slug</label>
<input type="text" class="form-control" name="slug" value="<?php echo @$row['slug']; ?>"></input>
</div>

<div class="col-md-12">
<label for="exampleInputPassword1">Slug</label>
<input type="text" class="form-control" name="dob" value="<?php echo @$row['dob']; ?>"></input>
</div>



<!-- <input type="hidden" name="id" value="<?php=$id?>"> -->
<button type="submit" name="update_page"  class="btn btn-primary mt-4 my-8" style="height:50px;" >Update</button>

</form> 

<?php
     include 'footer.php';
     ?>
     
</body>
</html>