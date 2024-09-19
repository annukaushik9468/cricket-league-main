<?php include 'dbconnection.php'; ?>


<?php

if(isset ($_POST['submit'])){
   $state_id = $_POST['state_id'];
        $name = $_POST['name'];
         $slug = $_POST['slug'];
         $dob = $_POST['dob'];
          @$images = mysqli_real_escape_string($con, $_FILES["pic"]['name']);

$insertquery = "insert into tbl_player(state_id, name, slug, dob, images) values('$state_id', '$name', '$slug', '$dob', '$images')";


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


<?php
include 'header.php';
?>

    <section class="section dashboard">
      <div class="row">

     <form action="" method="POST" enctype="multipart/form-data">
<div class="container my-3">
   <h2>Add Player Name</h2>
<div class="form-group ml-3" >
  <label for=""> <div class="row mb-3">
                  <!-- <label class="col-sm-2 col-form-label">Select</label> -->
                                   <div class="col-sm-8">
                    <select class="col-md-12 mt-1 mb-3 form-control" aria-label="Default select example" name="state_id">
                      <option selected>State id</option>
                        <?php
    $title = mysqli_query($con,"Select * from tbl_state");
    while($t = mysqli_fetch_assoc($title)){
      ?>
                     <option value=<?php echo $t['id'] ?>> <?php echo $t['title'] ?></option>
                     <?php } ?>
                    </select>
  </div>   
  <div class="form-group col-md-8 mt-1">
    <label for="exampleInputPassword1"></label>
    <input type="text" class="form-control" name="name" id="name" placeholder="Name" required>
  </div> 
   <div class="form-group col-md-8 mt-1">
    <label for="exampleInputPassword1"></label>
    <input type="text" class="form-control" name="slug" id="slug" placeholder="Slug" required>
  </div> 
  <div class="form-group col-md-8 mt-1">
    <label for="exampleInputPassword1"></label>
    <input type="text" class="form-control" name="dob" id="dob" placeholder="Date of Birth" required>
  </div>
   <div class="form-group col-md-12 mt-4" class="btn btn-info">
    <label for="Image">Upload photo</label>
    <input type="file" name="pic" id="pic" >
  </div>
  <button type="submit" name ="submit" id="submit" class="btn btn-primary col-sm-2 mt-4">Submit</button>
  </div>
</form>  

      <!-- </div>  -->

      </div>
    </section>

  </main><!-- End #main -->

  <?php
include 'footer.php';
?>