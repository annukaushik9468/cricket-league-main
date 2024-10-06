<?php
include 'dbconnection.php'
?>

<?php
include 'header.php';
?> 
      
    <section class="section dashboard">
      <div class="row">

       <?php
  if (isset($_POST['submit'])){ 
     @ $season_id = mysqli_real_escape_string($con, $_POST['season_id']);
    @ $series_id = mysqli_real_escape_string($con, $_POST['series_id']);
    @ $team_id = mysqli_real_escape_string($con, $_POST['team_id']);
   @ $player_name = mysqli_real_escape_string($con, $_POST['player_name']);
  //  @ $series = mysqli_real_escape_string($con, $_POST['series']);
   @ $player_id = $_POST['player_id'];
   

// $query = "INSERT INTO add_player (player_name) VALUES ";
// for ($i=0; $i<count($checkBox); $i++)
//     $query .= "('" . $checkBox[$i] . "'),";
// $query = rtrim($query,',');
// mysql_query($query) or die (mysql_error() );

  $seasonquery = "select * from tbl_season where title = '@$title'";
    $query = mysqli_query($con, $seasonquery);

    $seasoncount = mysqli_num_rows($query);

        $insertquery = "";
        for($i=0;$i<count($player_id);$i++)
        {
     
       $insertquery = "INSERT INTO tbl_teamplayer(season_id, series_id, team_id, player_name) VALUES ('$season_id', '$series_id', '$team_id', '$player_id[$i]')";
       
        $iquery = mysqli_query($con, $insertquery);
        }

        if($iquery){
           ?>
     <script>
      alert("inserted successful");
    </script> 
    <?php
    }else{
   ?>
    <script>
       alert(" no connection");
     </script>
    <?php
   }
      }

  // header("Location:index.php");
  ?>

 <div class="container my-3">
<form action="" method="POST" id="check" autocomplete="off" enctype="multipart/form-data">

<div class="container">
 <h2 class="text-center">Selection of Player in a team</h2>
  <table class="table">
          <thead>
            <tbody>

             <tr>
             <label for="exampleInput_name"><div class="row mb-3">
    <div class="col-sm-8">
    <select class="col-md-12 mt-3 mb-3 form-control" aria-label="Default select example" name="series_id">
                      <option selected>Series Id</option>
                      
                        <?php
    $title = mysqli_query($con, "Select * from tbl_series");
    while($s = mysqli_fetch_assoc($title)){
      ?>
              <option value = <?php echo $s['id'] ?>> <?php echo $s['title'] ?></option>
                     <?php } ?>
                    </select>       
    </div>   
 
    <div class="col-md-12">
    <select class="col-md-8 form-control" aria-label="Default select example" name="season_id">
                      <option selected>Season</option>
                      
                        <?php
    $title = mysqli_query($con, "Select * from tbl_season");
    while($s = mysqli_fetch_assoc($title)){
      ?>x
              <option value = <?php echo $s['id'] ?>> <?php echo $s['title'] ?></option>
                     <?php } ?>
                    </select>       
    </div>

    
    <div class="col-md-12 mt-3">
    <select class="col-md-8 form-control" aria-label="Default select example" name="team_id">
                      <option selected>Team Name</option>
                      
                        <?php
    $title = mysqli_query($con, "Select * from tbl_team");
    while($s = mysqli_fetch_assoc($title)){
      ?>
              <option value = <?php echo $s['id'] ?>> <?php echo $s['title'] ?></option>
                     <?php } ?>
                    </select>       
    </div>

  
              <th><div class="form-check form-check">
  <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="check" value="option1">
  <label class="form-check-label" for="inlineCheckbox1"></label>
</div>
</th>
              <th>id</th>
              <th>Player Name</th>
              <!-- <th>operations</th>  -->
      </tr>
    </thead> 
    <table class="table" id="mytable">
      

<?php
             @$id = $_POST['id'];
            $sql = "SELECT * FROM `tbl_player` ";
            $result = mysqli_query($con, $sql);
            $id = 0;

             while($row = mysqli_fetch_assoc($result)){
             $id = $id + 1;

echo '<tr>
<td><div class="form-check form-check">
  <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="player_id[]" value="'.$row["id"] .'">
  <label class="form-check-label" for="inlineCheckbox2"></label></td>
<td>'. $row["id"] . '</td>
<td>'. $row["name"] . '</td>
</tr>';
            }  
        ?>
        </table>
          </div>
  </div>
  </label>
</div>
 <button type="submit" name ="submit" class="btn btn-primary col-md-2 mt-4">Submit</button>
</form>
</div>

  </main><!-- End #main -->

 <?php
include 'footer.php';
 ?>

</body>

</html>