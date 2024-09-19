<?php
include 'dbconnection.php';
?>

<?php
include 'header.php';
?>

    <section class="section dashboard">
      <div class="row">


      </div>
    </section>

          <div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="text-center">Fetching the data </h4>
      </div>
      <div class="card-body">

        <?php
        $conn =  mysqli_connect("localhost", "root", "", "cric_stats");
        $query = "Select * FROM tbl_teamplayer";
        $query_run = mysqli_query($conn ,$query);
        ?>
        <table class="table">
          <thead>
            
            <tr>
            <th>id</th>
              <th>Series</th>
              <th>Season</th>
              <th>Team Id</th>
              <th>Player Name</th>
              <th>Upload Path</th>
              <th>Position</th>
              <th>operations</th>
            </tr>
            
          </thead>
          <tbody>
          <?php
          if(mysqli_num_rows($query_run) > 0)
          {
          
            foreach($query_run as $row)
            {
              $name = "Select * FROM tbl_teamplayer where id='".$row['id']."'";
              $name_run = mysqli_query($conn ,$name);
              $name_record = mysqli_fetch_array($name_run);
              
              
              ?>

              <tr>
                     <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['series_id']; ?></td>
                <td><?php echo $row['season_id']; ?></td>
                <td><?php echo @$row['team_id']; ?></td>
                <td><?php echo @$row['player_name']; ?></td>
                <td><?php echo @$row['upload_path']; ?></td>
                <td><?php echo @$row['position']; ?></td>
             
 <td>
  
           <a href='update8.php?id=<?php echo $row['id']; ?>'>Update</a>
          <a href='delete8.php?id=<?php echo $row['id']; ?>'>Delete</a>

            

           </td>
              </tr>
              <?php
            }
          }
          else
          {
            ?>

            <tr>
              <td>No result found</td>
            </tr>
            <?php
          }
          ?>
             
      
          </tbody>
        </table>
    </div>
  </div>
</div>
</div>
</div>




  </main><!-- End #main -->

  <?php
include 'footer.php';
?>

</body>

</html>

