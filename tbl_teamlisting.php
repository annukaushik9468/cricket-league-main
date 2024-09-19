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
        $query = "Select * FROM tbl_team";
        $query_run = mysqli_query($conn ,$query);
        ?>
        <table class="table">
          <thead>
            
            <tr>
            <th>ID</th>
              <th>Title</th>
               <th>Slug</th>
               <th>Operation</th>
            </tr>
            
          </thead>
          <tbody>
          <?php
          if(mysqli_num_rows($query_run) > 0)
          {
          
            foreach($query_run as $row)
            {
              $name = "Select * FROM tbl_team where id='".$row['id']."'";
              $name_run = mysqli_query($conn ,$name);
              $name_record = mysqli_fetch_array($name_run);
              
              
              ?>

              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['slug']; ?></td>
             
 <td>
  
           <a href='update5.php?id=<?php echo $row['id']; ?>'>Update</a>
          <a href='delete5.php?id=<?php echo $row['id']; ?>'>Delete</a>

            

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