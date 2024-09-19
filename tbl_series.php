<?php include 'dbconnection.php'; ?>


<?php

if(isset ($_POST['submit'])){
  $title = $_POST['title'];
  $slug = $_POST['slug']; 

$insertquery = "insert into tbl_series(title, slug) values('$title', '$slug')";


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
 <h2>Add Series Name</h2>
      <form action="" method="POST" enctype="multipart/form-data">
<div class="container my-3">

  <div class="form-group col-md-12 mt-3">
    <label for="exampleInputPassword1"></label>
    <input type="text" class="form-control" name="title" id="title" placeholder="Series" required>
  </div> 
   <div class="form-group col-md-12 mt-3">
    <label for="exampleInputPassword1"></label>
    <input type="text" class="form-control" name="slug" id="slug" placeholder="Slug" required>
  </div> 
  
  <button type="submit" name ="submit" id="submit" class="btn btn-primary col-sm-2 mt-4">Submit</button>
  </div>
</form> 
      </div>
    </section>

  </main><!-- End #main -->

  <?php
include 'footer.php';
?>

