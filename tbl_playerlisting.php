<?php
include 'dbconnection.php';
?>

<?php include 'header.php'; ?>

<!-- Search Form -->
<form method="get">
    <input type="text" placeholder="Search data" name="search">
    <button class="btn btn-warning btn-sm" name="submit">Search</button>
</form>

<!-- Search Results Display -->
<div class="container my-5">
    <table class="table">
        <?php
        if (isset($_GET['submit'])) { // Changed to $_GET to match the search form method
            $search = $_GET['search'];
            $sql = "SELECT * FROM `tbl_player` WHERE id LIKE '%$search%' OR name LIKE '%$search%'";
            $result = mysqli_query($con, $sql);
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<thead>
                    <tr>
                    <th>Id</th>
                    <th>Player Name</th>
                    </tr>
                    </thead>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tbody>
                        <tr>
                            <td>'.$row['id'].'</td>
                            <td>'.$row['name'].'</td>
                        </tr>
                        </tbody>';
                    }
                } else {
                    echo '<h2 class="text-danger">Data not found</h2>';
                }
            }
        }
        ?>
    </table>
</div>

<!-- Player List -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Player List Data</h4>
                </div>
                <div class="card-body">
                    <?php
                    $conn = mysqli_connect("localhost", "root", "", "cric_stats");
                    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>State Id</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Dob</th>
                                <th>Images</th>
                                <th>Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $name = "SELECT * FROM tbl_player ORDER BY id DESC"; // Sorting by ID DESC to get the latest entries last
                        if (@$_GET['search']) {
                            $search = $_GET['search'];
                            $name .= " WHERE name LIKE '%$search%'"; // Search filtering
                        }
                        $name_run = mysqli_query($conn, $name);
                            
                        if (mysqli_num_rows($name_run) > 0) {
                            while ($row = mysqli_fetch_array($name_run)) {
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['state_id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['slug']; ?></td>
                            <td><?php echo $row['dob']; ?></td>
                            <td><a href="<?php echo "images/". $row['images']; ?>" target="_blank">View image</a></td>
                            <td>
    <!-- Right tick for Edit with hover tooltip and direct navigation -->
    <a href="update.php?id=<?php echo $row['id']; ?>" 
       title="Edit" 
       style="text-decoration: none; cursor: pointer;">
        &#10003;
    </a>
    <!-- Wrong tick for Delete with hover tooltip -->
    <span title="Delete" 
          style="cursor: pointer;" 
          onclick="confirmDelete(<?php echo $row['id']; ?>)">
        &#10007;
    </span>
</td>

                        </tr>
                        <?php
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="7">No result found</td>
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
