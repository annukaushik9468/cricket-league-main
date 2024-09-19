<?php
include 'header.php';
include 'dbconnection.php';
?>

<div class="container">
<h2>Player Data List</h2>

<div class="row">
<table class="table table-striped table-bordered">
  <thead class="thead-dark">
    <tr>
      <th>ID</th>
      <th>Season Id</th>
      <th>Series Id</th>
      <th>Match No</th>
      <th>Bowler Name</th>
      <th>Dot Ball</th>
      <th>One Run</th>
      <th>Two Run</th>
      <th>Four Run</th>
      <th>Six Run</th>
      <th>wide Ball</th>
      <th>wicket</th>
</tr>
</thead>
<tbody>

<?php
$result = $db->query("SELECT * FROM tbl_team_player ORDER BY id DESC");
if($result->num_rows > 0){
  while($row = $result->fetch_assoc()){
    ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $row['series_id']; ?></td>
      <td><?php echo $row['season_id']; ?></td>
      <td><?php echo $row['match_no']; ?></td>
      <td><?php echo $row['bowler_name']; ?></td>
      <td><?php echo $row['dot_ball']; ?></td>
      <td><?php echo $row['one_run']; ?></td>
      <td><?php echo $row['two_run']; ?></td>
      <td><?php echo $row['four_run']; ?></td>
      <td><?php echo $row['six_run']; ?></td>
      <td><?php echo $row['wide_ball']; ?></td>
      <td><?php echo $row['wicket']; ?></td>
  </tr>
  <?php
  }
}else{
  ?>
  <tr><td colspan="5">No players Found</td></tr>
  <?php
}
?>
</tbody>
</table>
</div>
</div>

<?php
include 'footer.php';
  ?>