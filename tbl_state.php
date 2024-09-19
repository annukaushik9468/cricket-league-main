<?php include 'dbconnection.php'; ?>


<?php

if(isset ($_POST['submit'])){
  $country_id = $_POST['country_id'];
  $title = $_POST['title']; 

$insertquery = "insert into tbl_state(country_id, title) values('$country_id', '$title')";


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
  <h2>State Entry</h2>
<div class="form-group ml-3" >
  <label for=""> <div class="row mb-3">
                  <!-- <label class="col-sm-2 col-form-label">Select</label> -->
                                   <div class="col-sm-12">
                    <select class="col-md-12 mt-3 mb-3 form-control" aria-label="Default select example" name="country_id">
                      <option selected>Country id</option>
                        <?php
    $title = mysqli_query($con,"Select * from tbl_country");
    while($t = mysqli_fetch_assoc($title)){
      ?>
                     <option value=<?php echo $t['id'] ?>> <?php echo $t['title'] ?></option>
                     <?php } ?>
                    </select>
  </div>     
  <div class="form-group col-md-12 mt-3">
    <label for="exampleInputPassword1"></label>
    <input type="text" class="form-control" name="title" id="title" placeholder="State Name" required>
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

