<?php 
include 'dbconnection.php';
include 'header.php';
if (isset($_POST['update_page']))
{
if(count($_POST)>0) {
    $sql = "UPDATE tbl_teamselection SET series_id='" . $_POST['series_id'] . "',season_id='" . $_POST['season_id'] . "',match_no='" . $_POST['match_no'] . "',team_1='" . $_POST['team_1'] . "',team_2='" . $_POST['team_2'] . "',player_team1='" . $_POST['player_team1'] . "',player_team2='" . $_POST['player_team2'] . "'";
  
    $sql .= " WHERE id='" . $_GET['id'] . "'";
   if(mysqli_query($con, $sql))
  {
    // header('location:ma_listing.php');
  }

  $message = "<p style='color:green;'>Record modify succesfully !</p>";
}
}
$result = mysqli_query($con, "SELECT * FROM tbl_teamselection WHERE id='" . $_GET['id'] . "'");
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
<label for="exampleInputPassword1">Match No</label>
<input type="text" class="form-control" name="match_no" value="<?php echo $row['match_no']; ?>"></input>
</div>
<div class="col-md-12">
<label for="exampleInputPassword1">Team 1</label>
<input type="text" class="form-control" name="team_1" value="<?php echo $row['team_1']; ?>"></input>
</div>

<div class="col-md-12">
<label for="exampleInputPassword1">Team 2</label>
<input type="text" class="form-control" name="team_2" value="<?php echo $row['team_2']; ?>"></input>
</div>

<div class="col-md-12">
<label for="exampleInputPassword1">Team 2</label>
<input type="text" class="form-control" name="player_team1" value="<?php echo $row['player_team1']; ?>"></input>
</div>
<div class="col-md-12">
<label for="exampleInputPassword1">Team 2</label>
<input type="text" class="form-control" name="player_team2" value="<?php echo $row['player_team2']; ?>"></input>
</div>

<!-- <input type="hidden" name="id" value="<?php=$id?>"> -->
<button type="submit" name="update_page"  class="btn btn-primary mt-4 my-8" style="height:50px;" >Update</button>

</form> 

<?php
     include 'footer.php';
     ?>
     
</body>
</html>