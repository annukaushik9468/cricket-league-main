<?php
  //connect to the database
  $servername = "localhost";
    $user = "root";
    $password = "";
    $database = "cric_stats";
    //create a connection 
    $con = mysqli_connect($servername, $user, $password, $database);

   if($con){
    ?>
  <!-- <script>
      alert("connection successful");
    </script>    -->
    <?php
   }else{
?>
   <script>
      alert("no connection");
    </script>
    <?php
   }
?>

