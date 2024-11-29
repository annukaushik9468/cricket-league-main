<?php
@$id = $_GET['id'];

$con = mysqli_connect("localhost", "root", "", "cric_stats") or die("Connection Failed");

$sql = "DELETE FROM tbl_series WHERE id = {$id}";
$result = mysqli_query($con, $sql);

if($result) {
    // Redirect to the player listing page after successful deletion
    header("Location: tbl_serieslisting.php");
    exit();  // Always call exit after header redirection to stop further script execution
} else {
    echo "Error deleting record: " . mysqli_error($con);
}

mysqli_close($con);
?>
