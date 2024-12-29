<?php
include 'dbconnection.php';
?>

<?php include 'header.php'; ?>

<section class="section dashboard">
  <div class="row"></div>
</section>

<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="text-center">Fetching the data Of Team</h4>
        </div>
        <div class="card-body">

          <?php
          $conn = mysqli_connect("localhost", "root", "", "cric_stats");

          // Fetching data with a join to get series title
          $query = "
            SELECT t.id, t.title AS team_title, t.slug, t.series_id, s.title AS series_title 
            FROM tbl_team t
            LEFT JOIN tbl_series s ON t.series_id = s.id
            ORDER BY t.id DESC";
          $query_run = mysqli_query($conn, $query);
          ?>
          <table class="table table-bordered table-striped">
            <thead class="thead-dark">
              <tr>
                <th>ID</th>
                <th>Team Title</th>
                <th>Slug</th>
                <th>Series Title</th>
                <th>Operations</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (mysqli_num_rows($query_run) > 0) {
                  foreach ($query_run as $row) {
              ?>
              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['team_title']; ?></td>
                <td><?php echo $row['slug']; ?></td>
                <td><?php echo $row['series_title'] ? $row['series_title'] : 'N/A'; ?></td>
                <td>
                  <!-- Right tick for Edit with tooltip -->
                  <a href="update5.php?id=<?php echo $row['id']; ?>" 
                     title="Edit" 
                     style="cursor: pointer; font-size: 20px; color: green; text-decoration: none;">
                    &#10003;
                  </a>

                  <!-- Wrong tick for Delete with tooltip -->
                  <a href="delete5.php?id=<?php echo $row['id']; ?>" 
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
                <td colspan="5" class="text-center">No result found</td>
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

<?php include 'footer.php'; ?>
</body>
</html>
