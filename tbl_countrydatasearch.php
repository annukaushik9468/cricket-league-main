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
        <div class="row">
          <div class="col-md-8">
             <form action="" method="GET">
            <div class="input-group mb-3">
   <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control" placeholder="Search data">
   <button type="submit" class="btn btn-primary">Search</button>
 </div>
</form>
          </div>
        </div>
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
        $conn =  mysqli_connect("localhost", "root", "", "cric_stats");
        if(isset($_GET['search'])){
          $filtervalues = $_GET['search'];
        $query = "SELECT * FROM tbl_country WHERE CONCAT(title,slug ) LIKE '%$filtervalues%' ";
        $query_run = mysqli_query($conn ,$query);

          if(mysqli_num_rows($query_run) > 0)
          {
          
            foreach($query_run as $row)
            {
              ?>
       

              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['slug']; ?></td>
             
 <td>
  
           <a href='update1.php?id=<?php echo $row['id']; ?>'>Update</a>
          <a href='delete1.php?id=<?php echo $row['id']; ?>'>Delete</a>

            

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