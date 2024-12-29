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
            $series = $conn->query("SELECT id, title FROM tbl_series");
            $stadium = $conn->query("SELECT id, title FROM tbl_stadium");
            ?>
            
            <form action="" method="post" enctype="multipart/form-data">
                <label for="season_id">Season ID:</label>
                <select name="season_id" id="season_id" class="form-control">
                    <?php while ($row = $seasons->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="series_id">Series ID:</label>
                <select name="series_id" id="series_id" class="form-control" onchange="fetchMatches()">
                    <option value="">Select Series</option>
                    <?php while ($row = $series->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                    <?php endwhile; ?>
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

<?php
if (isset($_POST['submit'])) {
    $season_id = $_POST['season_id'];
    $series_id = $_POST['series_id'];
    $stadium_id = $_POST['stadium_id'];
    $team_match = $_POST['team_match'];
    $file = $_FILES['file']['tmp_name'];

    if (empty($file) || !file_exists($file)) {
        echo "No file selected or file upload failed.";
        exit;
    }

    $conn = new mysqli('localhost', 'root', '', 'cric_stats');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $match_query = "SELECT team_1, team_2 FROM tbl_matches WHERE id = '$team_match' LIMIT 1";
    $match_result = $conn->query($match_query);
    if ($match_result->num_rows > 0) {
        $match_row = $match_result->fetch_assoc();
        $team1_id = $match_row['team_1'];
        $team2_id = $match_row['team_2'];
    } else {
        echo "Match details not found.";
        exit;
    }

    if (($handle = fopen($file, 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $match_no = $data[0];
        $battsman = $data[1];
        $bowler = $data[2];
        $dot_ball = $data[3];
        $one_run = $data[4];
        $two_run = $data[5];
        $three_run = $data[6];
        $four_run = $data[7];
        $six_run = $data[8];
        $wide = $data[9];
        $wicket = $data[10];

        // Generate a unique ID
        $unique_id = uniqid('record_', true);

        $batsman_query = "SELECT id FROM tbl_player WHERE name = '$battsman' LIMIT 1";
        $batsman_result = $conn->query($batsman_query);
        if ($batsman_result->num_rows > 0) {
            $batsman_row = $batsman_result->fetch_assoc();
            $batsman_id = $batsman_row['id'];
        } else {
            echo "Batsman '$battsman' not found.";
            continue;
        }

        $bowler_query = "SELECT id FROM tbl_player WHERE name = '$bowler' LIMIT 1";
        $bowler_result = $conn->query($bowler_query);
        if ($bowler_result->num_rows > 0) {
            $bowler_row = $bowler_result->fetch_assoc();
            $bowler_id = $bowler_row['id'];
        } else {
            echo "Bowler '$bowler' not found.";
            continue;
        }

        $sql = "INSERT INTO tbl_team_record 
                (unique_id, match_id, season_id, series_id, stadium_id, team_1, team_2, match_no, battsman, bowler, dot_ball, one_run, two_run, three_run, four_run, six_run, wide, wicket) 
                VALUES 
                ('$unique_id', '$team_match', '$season_id', '$series_id', '$stadium_id', '$team1_id', '$team2_id', '$match_no', '$batsman_id', '$bowler_id', '$dot_ball', '$one_run', '$two_run', '$three_run', '$four_run', '$six_run', '$wide', '$wicket')";

        if (!$conn->query($sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    fclose($handle);
    echo "Data inserted successfully.";
} else {
    echo "Error opening the file.";
}


    $conn->close();
}
?>

<?php include 'footer.php'; ?>
