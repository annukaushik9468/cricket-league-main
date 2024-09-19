<?php include 'dbconnection.php'; ?>


<?php

if(isset ($_POST['submit'])){
  $series_id = $_POST['series_id'];
  $season_id = $_POST['season_id'];
   $match_no = $_POST['match_no']; 
    $team_1 = $_POST['team_1']; 
     $team_2 = $_POST['team_2']; 

$insertquery = "insert into tbl_schedule(series_id, season_id, match_no, team_1, team_2) values('$series_id','$season_id', '$match_no', '$team_1', '$team_2')";


$res = mysqli_query($con,$insertquery);

if($res){
  ?>
  <script>
    alert("data was inserted successfully.");
  </script>
  <?php
}else{
  ?>
  <script>
    alert("data was not inserted.");
  </script>
  <?php
}
}

?>

<?php include 'header.php'; ?>
 <section class="section dashboard">
      <div class="row">

      <form action="" method="POST" enctype="multipart/form-data">
<div class="container my-3">
  <h2>Add Schedule Entry</h2>
<div class="form-group ml-3" >
  <label for=""> <div class="row mb-3">
                                   <div class="col-sm-12">
                    <select class="col-md-12 mt-3 mb-3 form-control" aria-label="Default select example" name="series_id">
                      <option selected>Series id</option>
                        <?php
    $title = mysqli_query($con,"Select * from tbl_series");
    while($t = mysqli_fetch_assoc($title)){
      ?>
                     <option value=<?php echo $t['id'] ?>> <?php echo $t['title'] ?></option>
                     <?php } ?>
                    </select>
  </div> 
     
  <div class="col-sm-12">
                    <select class="col-md-12 mt-3 mb-3 form-control" aria-label="Default select example" name="season_id">
                      <option selected>Season id</option>
                        <?php
    $title = mysqli_query($con,"Select * from tbl_season");
    while($t = mysqli_fetch_assoc($title)){
      ?>
                     <option value=<?php echo $t['id'] ?>> <?php echo $t['title'] ?></option>
                     <?php } ?>
                    </select>
  </div>     

<div class="form-group col-md-12">
    <label for="exampleInputPassword1"></label>
    <input type="text" class="form-control" name="match_no" id="match_no" placeholder="Match Number" required>
  </div>

                                   <div class="col-sm-12">
                    <select class="col-md-12 mt-3 mb-3 form-control" aria-label="Default select example" name="team_1">
                      <option selected>Team 1</option>
                        <?php
    $title = mysqli_query($con,"Select * from tbl_team");
    while($t = mysqli_fetch_assoc($title)){
      ?>
                     <option value=<?php echo $t['id'] ?>> <?php echo $t['title'] ?></option>
                     <?php } ?>
                    </select>
  </div>     
   
                                   <div class="col-sm-12">
                    <select class="col-md-12 mt-3 mb-3 form-control" aria-label="Default select example" name="team_2">
                      <option selected>Team 2</option>
                        <?php
    $title = mysqli_query($con,"Select * from tbl_team");
    while($t = mysqli_fetch_assoc($title)){
      ?>
                     <option value=<?php echo $t['id'] ?>> <?php echo $t['title'] ?></option>
                     <?php } ?>
                    </select>
  </div>
  </div>     
  <button type="submit" name ="submit" id="submit" class="btn btn-primary col-sm-2 mt-2">Submit</button>
  </div>
</form> 
      </div>
    </section>

  </main><!-- End #main -->

  <?php
include 'footer.php';
?>

