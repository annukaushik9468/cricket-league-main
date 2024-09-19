<?php 
include 'dbconnection.php';
include 'header.php';
if (isset($_POST['update_page']))
{
if(count($_POST)>0) {
    $sql = "UPDATE tbl_series SET title='" . $_POST['title'] . "',slug='" . $_POST['slug'] . "'";
  
    $sql .= " WHERE id='" . $_GET['id'] . "'";
   if(mysqli_query($con, $sql))
  {
    // header('location:ma_listing.php');
  }

  $message = "<p style='color:green;'>Record modify succesfully !</p>";
}
}
$result = mysqli_query($con, "SELECT * FROM tbl_series WHERE id='" . $_GET['id'] . "'");
$row= mysqli_fetch_array($result);
// print_r($updatedata)
?>

<form action="" method="POST" enctype="multipart/form-data">


<div class="col-md-12">
<label for="exampleInputPassword1">Title</label>
<input type="text" class="form-control" name="title" value="<?php echo $row['title']; ?>"></input>
</div>


<div class="col-md-12">
<label for="exampleInputPassword1">Slug</label>
<input type="text" class="form-control" name="slug" value="<?php echo $row['slug']; ?>"></input>
</div>

<!-- <input type="hidden" name="id" value="<?php=$id?>"> -->
<button type="submit" name="update_page"  class="btn btn-primary mt-4 my-8" style="height:50px;" >Update</button>

</form> 

<?php
     include 'footer.php';
     ?>
     
</body>
</html>