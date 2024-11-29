<?php include 'dbconnection.php'; ?>

<?php
if (isset($_POST['submit'])) {
    $series_id = $_POST['series_id'];
    $season_id = $_POST['season_id'];
    $stadium_id = $_POST['stadium_id'];
    $match_no = $_POST['match_no'];
    $team_1 = $_POST['team_1'];
    $team_2 = $_POST['team_2'];

    // Check if the match number already exists for the selected series
    $checkQuery = "SELECT * FROM tbl_schedule WHERE series_id = '$series_id' AND match_no = '$match_no'";
    $result = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // Match number already exists
        echo "<script>alert('The match number already exists for this series. Please select a different match number.');</script>";
    } else {
        // Insert the new match details
        $insertquery = "INSERT INTO tbl_schedule(series_id, season_id, stadium_id, match_no, team_1, team_2) 
                        VALUES('$series_id', '$season_id', '$stadium_id', '$match_no', '$team_1', '$team_2')";

        $res = mysqli_query($con, $insertquery);

        if ($res) {
            echo "<script>alert('Data was inserted successfully.');</script>";
        } else {
            echo "<script>alert('Data was not inserted.');</script>";
        }
    }
}
?>

<?php include 'header.php'; ?>
<section class="section dashboard">
    <div class="row">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="container my-3">
                <h2>Add Schedule Entry</h2>
                <div class="form-group ml-3">
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <select class="col-md-12 mt-3 mb-3 form-control" name="series_id" required>
                                <option value="" selected>Select Series</option>
                                <?php
                                $series = mysqli_query($con, "SELECT * FROM tbl_series");
                                while ($row = mysqli_fetch_assoc($series)) {
                                    echo "<option value='{$row['id']}'>{$row['title']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <select class="col-md-12 mt-3 mb-3 form-control" name="season_id" required>
                                <option value="" selected>Select Season</option>
                                <?php
                                $seasons = mysqli_query($con, "SELECT * FROM tbl_season");
                                while ($row = mysqli_fetch_assoc($seasons)) {
                                    echo "<option value='{$row['id']}'>{$row['title']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <select class="col-md-12 mt-3 mb-3 form-control" name="stadium_id" required>
                                <option value="" selected>Select Stadium</option>
                                <?php
                                $stadiums = mysqli_query($con, "SELECT * FROM tbl_stadium");
                                while ($row = mysqli_fetch_assoc($stadiums)) {
                                    echo "<option value='{$row['id']}'>{$row['title']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" name="match_no" placeholder="Match Number" required>
                        </div>
                        <div class="col-sm-12">
                            <select class="col-md-12 mt-3 mb-3 form-control" id="team_1" name="team_1" required>
                                <option value="" selected>Select Team 1</option>
                                <?php
                                $teams = mysqli_query($con, "SELECT * FROM tbl_team");
                                while ($row = mysqli_fetch_assoc($teams)) {
                                    echo "<option value='{$row['id']}'>{$row['title']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <select class="col-md-12 mt-3 mb-3 form-control" id="team_2" name="team_2" required>
                                <option value="" selected>Select Team 2</option>
                                <?php
                                $teams = mysqli_query($con, "SELECT * FROM tbl_team");
                                while ($row = mysqli_fetch_assoc($teams)) {
                                    echo "<option value='{$row['id']}'>{$row['title']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary col-sm-2 mt-2">Submit</button>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    document.getElementById('team_1').addEventListener('change', function () {
        const selectedTeam1 = this.value;
        const team2Dropdown = document.getElementById('team_2');
        Array.from(team2Dropdown.options).forEach(option => {
            option.style.display = option.value === selectedTeam1 ? 'none' : 'block';
        });
        team2Dropdown.value = ""; // Reset team_2 selection
    });
</script>

<?php include 'footer.php'; ?>
