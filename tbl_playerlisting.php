<?php
include 'dbconnection.php';

?>


 <?php include 'header.php'; ?>

    <!-- <div class="container my-5"> -->
		<form method="get">
		<input type="text" placeholder="Search data" name="search"> 
		<button class="btn btn-warning btn-sm" name="submit">Search</button>
		</form>
		<div class="container my-5">
			<table class="table">
				<?php
                  if(isset($_POST['submit'])){
                  	$search=$_POST['search'];

                  	$sql = "Select * from `tbl_player` where id like '%$search%' or name like '%$search%'";
                  	$result= mysqli_query($con,$sql);
                  	if($result){
                  		if(mysqli_num_rows($result)>0){
                  			echo '<thead>
                  			<tr>
                  			<th>Id</th>
                  			<th>Player Name</th>
                  			</tr>
                  			</thead>
                  			';
                  			$row=mysqli_fetch_assoc($result);
                  			echo '<tbody>
                  			<tr>
                            <td>'.$row['id'].'</td>
                             <td>'.$row['name'].'</td>
                  			</tr>
                  			</tbody>';
                  		}else{
                  			echo '<h2 class=text-danger>Data not found</h2>';
                  		}
                  	}
                  }
				?>
		
			</table>
		</div>
	</div>

      	  </div>

    <!-- <div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card"> -->
        <div class="card-header">
          <h4>Player list Data</h4>
      </div>
      <div class="card-body">
        <?php
        $conn =  mysqli_connect("localhost", "root", "", "cric_stats");
       
        ?>
        <table class="table">
          <thead>
            
            <tr>
              <th>id</th>
              <th>State Id</th>
              <th>Name</th>
              <th>Slug</th>
              <th>Dob</th>
              <th>Images</th>
              <th>operations</th>

            </tr>
            
          </thead>
          <tbody>
          <?php

           
$name = "Select * FROM tbl_player";
if(@$_GET['search'])
{
  $search = $_GET['search'];
  $name .= " where name like '%$search%'";
}
$name_run = mysqli_query($conn ,$name);
              
              if(mysqli_num_rows($name_run) > 0)
              {
              while($row = mysqli_fetch_array($name_run))
              {
              
              ?>

              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['state_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo @$row['slug']; ?></td>
                <td><?php echo @$row['dob']; ?></td>
                <td><a href="<?php echo "images/". $row['images']; ?> ">View image</td>
             
 <td>

          
          <a href='update.php?id=<?php echo $row['id']; ?>'>Update</a>
          <a href='delete.php?id=<?php echo $row['id']; ?>'>Delete</a>
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

  <?php include 'footer.php'; ?>