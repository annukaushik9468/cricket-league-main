<?php
include 'dbconnection.php';
?>

<?php include 'header.php'; ?>

<section class="section dashboard">
    <div class="row">
      <!-- Section content here (optional) -->
    </div>
</section>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Schedule Data</h4>
                </div>
                <div class="card-body">

                    <?php
                    $conn = mysqli_connect("localhost", "root", "", "cric_stats");

                    // Modify the query to fetch data in descending order of 'id'
                    $query = "SELECT a.id, a.match_no, b.title AS series_title, c.title AS season_title, d.title AS team_1, e.title AS team_2, f.title AS stadium_title 
                              FROM tbl_schedule a 
                              LEFT JOIN tbl_series b ON a.series_id = b.id
                              LEFT JOIN tbl_season c ON a.season_id = c.id
                              LEFT JOIN tbl_team d ON a.team_1 = d.id
                              LEFT JOIN tbl_team e ON a.team_2 = e.id
                              LEFT JOIN tbl_stadium f ON a.stadium_id = f.id
                              ORDER BY a.id DESC"; // Added ORDER BY to sort in descending order
                    $query_run = mysqli_query($conn, $query);
                    ?>

                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>id</th>
                                <th>Series</th>
                                <th>Season</th>
                                <th>Stadium</th>
                                <th>Match No</th>
                                <th>Team 1</th>
                                <th>Team 2</th>
                                <th>Status</th>
                                <th>Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_array($query_run)) {
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['series_title']; ?></td>
                                <td><?php echo $row['season_title']; ?></td>
                                <td><?php echo $row['stadium_title']; ?></td>
                                <td><?php echo $row['match_no']; ?></td>
                                <td><?php echo $row['team_1']; ?></td>
                                <td><?php echo $row['team_2']; ?></td>
                                <td> 
                                 <!-- Status Button for archiving -->
    <a href="archive_schedule.php?id=<?php echo $row['id']; ?>" 
       title="Archive" 
        style="cursor: pointer; font-size: 14px; color: white; background-color: blue; border: none; padding: 5px 10px; border-radius: 5px;">
                                            Archive
    </a></td>
                                 <td>
    <!-- Right tick for Edit with tooltip -->
    <a href="update6.php?id=<?php echo $row['id']; ?>" 
       title="Edit" 
       style="cursor: pointer; font-size: 20px; color: green; text-decoration: none;">
        &#10003;
    </a>

    <!-- Wrong tick for Delete with tooltip -->
    <a href="delete6.php?id=<?php echo $row['id']; ?>" 
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

<?php include 'footer.php'; ?>
</body>
</html>
