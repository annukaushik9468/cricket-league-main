<?php
include 'dbconnection.php';

?>

 <?php include 'header.php'; ?>


  <main id="main" class="main">

    <div class="pagetitle">
     
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
      </div>
    </section>

    <div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Schedule Data</h4>
      </div>
      <div class="card-body">
        <?php
        $conn =  mysqli_connect("localhost", "root", "", "cric_stats");
        $query = "Select a.id,a.match_no,a.player_team1,a.player_team2,b.title as series_title,c.title as season_title,d.title as team_1,e.title as team_2 FROM tbl_teamselection a 
        left join  tbl_series b on a.series_id = b.id
        left join  tbl_season c on a.season_id=c.id
        left join  tbl_team d on a.team_1=d.id
        left join  tbl_team e on a.team_2=e.id";
        $query_run = mysqli_query($conn ,$query);
        ?>
        <table class="table">
          <thead>
            
            <tr>
              <th>id</th>
              <th>Series</th>
              <th>Season</th>
              <th>Match No</th>
              <th>Team 1</th>
              <th>Team 2</th>
              <th>Player Team 1</th>
              <th>Player Team 2</th>
              <th>operations</th>

            </tr>
            
          </thead>
          <tbody>
          <?php
          if(mysqli_num_rows($query_run) > 0)
          {
            while($row = mysqli_fetch_array($query_run))
            {
             
              ?>

              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['series_title']; ?></td>
                <td><?php echo $row['season_title']; ?></td>
                <td><?php echo @$row['match_no']; ?></td>
                <td><?php echo @$row['team_1']; ?></td>
                <td><?php echo @$row['team_2']; ?></td>
                <td><?php echo @$row['player_team1']; ?></td>
                <td><?php echo @$row['player_team2']; ?></td>
               
 <td>
          <a href='update7.php?id=<?php echo $row['id']; ?>'>Update</a>
           <a href='delete7.php?id=<?php echo $row['id']; ?>'>Delete</a>
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

  <?php include 'footer.php' ?>