<?php
include 'dbconnection.php';
include 'header.php';
?>

<main id="main" class="main">
    <div class="pagetitle"></div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <?php
            $conn = new mysqli('localhost', 'root', '', 'cric_stats');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $seasons = $conn->query("SELECT id, title FROM tbl_season");
            $stadium = $conn->query("SELECT id, title FROM tbl_stadium");
            ?>

            <form action="" method="post" enctype="multipart/form-data">
                <label for="season_id">Season ID:</label>
                <select name="season_id" id="season_id" class="form-control" onchange="fetchSeries()">
                    <option value="">Select Season</option>
                    <?php while ($row = $seasons->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="series_id">Series ID:</label>
                <select name="series_id" id="series_id" class="form-control" onchange="fetchMatches()">
                    <option value="">Select Season First</option>
                </select><br>

                <label for="stadium_id">Stadium ID:</label>
                <select name="stadium_id" id="stadium_id" class="form-control">
                    <?php while ($row = $stadium->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="team_match">Teams:</label>
                <select name="team_match" id="team_match" class="form-control">
                    <option value="">Select Series First</option>
                </select><br>

                <label for="file">CSV File:</label>
                <input type="file" name="file" id="file"><br>

                <input type="submit" name="submit" class="my-2 btn btn-danger" value="Insert Data">
            </form>

            <?php $conn->close(); ?>
        </div>
    </section>
</main><!-- End #main -->

<script>
    function fetchSeries() {
        const seasonId = document.getElementById('season_id').value;
        const seriesSelect = document.getElementById('series_id');

        seriesSelect.innerHTML = '<option value="">Fetching series...</option>';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'fetch_series.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status === 200) {
                seriesSelect.innerHTML = this.responseText;
            } else {
                seriesSelect.innerHTML = '<option value="">Error fetching series</option>';
            }
        };
        xhr.send('season_id=' + seasonId);
    }

    function fetchMatches() {
        const seriesId = document.getElementById('series_id').value;
        const teamMatchSelect = document.getElementById('team_match');

        teamMatchSelect.innerHTML = '<option value="">Fetching matches...</option>';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'fetch_teams.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status === 200) {
                teamMatchSelect.innerHTML = this.responseText;
            } else {
                teamMatchSelect.innerHTML = '<option value="">Error fetching matches</option>';
            }
        };
        xhr.send('series_id=' + seriesId);
    }
</script>

<?php include 'footer.php'; ?>
