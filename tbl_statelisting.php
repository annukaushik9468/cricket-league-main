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
        $query = "Select a.id,a.title,b.title as country_title FROM tbl_state a 
        left join  tbl_country b on a.country_id = b.id";
        $query_run = mysqli_query($conn ,$query);
        ?>
        <table class="table">
          <thead>
            
            <tr>
              <th>id</th>
              <th>Country ID</th>
              <th>Title</th>
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
                <td><?php echo $row['country_title']; ?></td>
                <td><?php echo $row['title']; ?></td>
 <td>

         
          <a href='update2.php?id=<?php echo $row['id']; ?>'>Update</a>
           <a href='delete2.php?id=<?php echo $row['id']; ?>'>Delete</a>
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