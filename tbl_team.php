<?php
include 'dbconnection.php'; 

if (isset($_POST['submit'])) {
  $series_id = $_POST['series_id'];  // Get the series_id from the form
  $title = $_POST['title'];
  $slug = $_POST['slug'];

  // Check if the team already exists
  $checkQuery = "SELECT * FROM tbl_team WHERE title = '$title'";
  $checkResult = mysqli_query($con, $checkQuery);

  if (mysqli_num_rows($checkResult) > 0) {
    // If the team already exists
    ?>
    <script>
      alert("Team already exists.");
    </script>
    <?php
  } else {
    // If the team does not exist, insert it
    $insertquery = "INSERT INTO tbl_team(series_id, title, slug) VALUES('$series_id', '$title', '$slug')";
    $res = mysqli_query($con, $insertquery);

    if ($res) {
      ?>
      <script>
        alert("Data was inserted successfully.");
      </script>
      <?php
    } else {
      ?>
      <script>
        alert("Data was not inserted.");
      </script>
      <?php
    }
  }
}
?>

<?php include 'header.php'; ?>
<section class="section dashboard">
  <div class="row">
    <h2>Add Team Name</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="container my-3">
        <div class="form-group ml-3">
          <label for="series_id">Series</label>
          <div class="row mb-3">
            <div class="col-sm-12">
              <select class="col-md-12 mt-3 mb-3 form-control" aria-label="Default select example" name="series_id" required>
                <option value="">Select Series</option>  <!-- Default option to select -->
                <?php
                $seriesQuery = mysqli_query($con, "SELECT * FROM tbl_series");
                while ($series = mysqli_fetch_assoc($seriesQuery)) {
                  echo "<option value='" . $series['id'] . "'>" . $series['title'] . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group col-md-12 mt-3">
          <label for="title">Team Name</label>
          <input type="text" class="form-control" name="title" id="title" placeholder="Team Name" required>
        </div>
        
        <div class="form-group col-md-12 mt-3">
          <label for="slug">Slug</label>
          <input type="text" class="form-control" name="slug" id="slug" placeholder="Slug" required>
        </div>

        <button type="submit" name="submit" id="submit" class="btn btn-primary col-sm-2 mt-4">Submit</button>
      </div>
    </form>
  </div>
</section>

</main><!-- End #main -->

<?php include 'footer.php'; ?>
