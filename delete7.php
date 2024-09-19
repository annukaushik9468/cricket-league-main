<?php
@$id = $_GET['id'];

$con = mysqli_connect("localhost","root","","cric_stats") or die("connection Failed");

// @$sno = $_GET['sno'];

$sql = "DELETE FROM tbl_teamselection WHERE id = {$id}";
$result = mysqli_query($con, $sql) or die("Query unsuccessful.");

?>