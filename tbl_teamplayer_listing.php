<?php
include 'dbconnection.php';
?>

<?php
include 'header.php';
?>

<section class="section dashboard">
  <div class="row">
    <!-- Dashboard section content here -->
  </div>
</section>

<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="text-center">Fetching the Data</h4>
        </div>
        <div class="card-body">
          <?php
          // Connect to the database
          $conn = mysqli_connect("localhost", "root", "", "cric_stats");
          
          // Fetch data in descending order by ID
          $query = "SELECT * FROM tbl_teamplayer ORDER BY id DESC";
          $query_run = mysqli_query($conn, $query);
          ?>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Id</th>
                <th>Series</th>
                <th>Season</th>
                <th>Team Id</th>
                <th>Player Name</th>
                <th>Upload Path</th>
                <th>Position</th>
                <th>Operations</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (mysqli_num_rows($query_run) > 0) {
                while ($row = mysqli_fetch_assoc($query_run)) {
                  // Example conditions for enabling update/delete
                  $update_condition = true;  // Replace with real condition
                  $delete_condition = true;  // Replace with real condition
              ?>
                  <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['series_id']; ?></td>
                    <td><?php echo $row['season_id']; ?></td>
                    <td><?php echo $row['team_id']; ?></td>
                    <td><?php echo $row['player_name']; ?></td>
                    <td><?php echo $row['upload_path']; ?></td>
                    <td><?php echo $row['position']; ?></td>
                      <td>
    <!-- Right tick for Edit with tooltip -->
    <a href="update8.php?id=<?php echo $row['id']; ?>" 
       title="Edit" 
       style="cursor: pointer; font-size: 20px; color: green; text-decoration: none;">
        &#10003;
    </a>

    <!-- Wrong tick for Delete with tooltip -->
    <a href="delete8.php?id=<?php echo $row['id']; ?>" 
       title="Delete" 
       style="cursor: pointer; font-size: 20px; color: red; text-decoration: none;">
        &#10007;
    </a>
</td>

                  </tr>
              <?php
                }
              } else {
              ?>
                <tr>
                  <td colspan="8" class="text-center">No result found</td>
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

<script>
    // Function to confirm deletion
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            window.location.href = 'delete.php?id=' + id; // Redirect to the delete page
        }
    }
</script>


<?php
include 'footer.php';
?>
