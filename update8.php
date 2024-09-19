<?php 
include 'dbconnection.php';
include 'header.php';
if (isset($_POST['update_page']))
{
if(count($_POST)>0) {
    $sql = "UPDATE tbl_teamplayer SET series_id='" . $_POST['series_id'] . "',season_id='" . $_POST['season_id'] . "',team_id='" . $_POST['team_id'] . "',player_name='" . $_POST['player_name'] . "',upload_path='" . $_POST['upload_path'] . "',position='" . $_POST['position'] . "'";
  
    $sql .= " WHERE id='" . $_GET['id'] . "'";
   if(mysqli_query($con, $sql))
  {
    // header('location:ma_listing.php');
  }

  $message = "<p style='color:green;'>Record modify succesfully !</p>";
}
}
$result = mysqli_query($con, "SELECT * FROM tbl_teamplayer WHERE id='" . $_GET['id'] . "'");
$row= mysqli_fetch_array($result);
// print_r($updatedata)
?>

<form action="" method="POST" enctype="multipart/form-data">


<div class="col-md-12">
<label for="exampleInputPassword1">Series Id</label>
<input type="text" class="form-control" name="series_id" value="<?php echo $row['series_id']; ?>"></input>
</div>
<div class="col-md-12">
<label for="exampleInputPassword1">Season Id</label>
<input type="text" class="form-control" name="season_id" value="<?php echo $row['season_id']; ?>"></input>
</div>

<div class="col-md-12">
<label for="exampleInputPassword1">Team ID</label>
<input type="text" class="form-control" name="team_1" value="<?php echo $row['team_id']; ?>"></input>
</div>

<div class="col-md-12">
<label for="exampleInputPassword1">Player Name</label>
<input type="text" class="form-control" name="player_name" value="<?php echo $row['player_name']; ?>"></input>
</div>

<div class="col-md-12">
<label for="exampleInputPassword1">Upload Path</label>
<input type="text" class="form-control" name="upload_path" value="<?php echo $row['upload_path']; ?>"></input>
</div>
<div class="col-md-12">
<label for="exampleInputPassword1">Position</label>
<input type="text" class="form-control" name="Position" value="<?php echo $row['position']; ?>"></input>
</div>

<!-- <input type="hidden" name="id" value="<?php=$id?>"> -->
<button type="submit" name="update_page"  class="btn btn-primary mt-4 my-8" style="height:50px;" >Update</button>

</form> 

<?php
     include 'footer.php';
     ?>
     
</body>
</html>