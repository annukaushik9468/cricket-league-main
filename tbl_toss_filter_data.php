<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "cric_stats");

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data for dropdowns from tbl_schedule
function fetchScheduleOptions($conn, $column) {
    $options = "";
    $query = "SELECT DISTINCT $column FROM tbl_schedule";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $options .= "<option value='" . $row[$column] . "'>" . $row[$column] . "</option>";
        }
    } else {
        echo "Error fetching options: " . $conn->error;
    }
    return $options;
}

// Fetch player options for checkboxes
function fetchPlayerOptions($conn) {
    $options = "";
    $query = "SELECT id, name FROM tbl_player";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $options .= "<label><input type='checkbox' name='players[]' value='" . $row['id'] . "'> " . $row['name'] . "</label><br>";
        }
    } else {
        echo "Error fetching player options: " . $conn->error;
    }
    return $options;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $season_id = isset($_POST['season_id']) ? $_POST['season_id'] : null;
    $series_id = isset($_POST['series_id']) ? $_POST['series_id'] : null;
    $match_no = isset($_POST['match_no']) ? $_POST['match_no'] : null;
    $team_1 = isset($_POST['team_1']) ? $_POST['team_1'] : null;
    $team_2 = isset($_POST['team_2']) ? $_POST['team_2'] : null;
    $players = isset($_POST['players']) ? $_POST['players'] : [];
    $upload_path = isset($_FILES['upload_path']) ? $_FILES['upload_path']['tmp_name'] : null;

    // Validate if required fields are selected
    if ($season_id && $series_id && $match_no && $team_1 && $team_2 && !empty($players) && $upload_path) {
        // Process the file upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["upload_path"]["name"]);
        if (move_uploaded_file($_FILES["upload_path"]["tmp_name"], $target_file)) {
            echo "File uploaded successfully.";
        } else {
            echo "Error uploading file.";
            exit;
        }

        // Fetch batting_team and toss_winner from tbl_matches
        $query = "SELECT batting_team, toss_winner FROM tbl_matches WHERE team_1 = ? AND team_2 = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ii", $team_1, $team_2);
            $stmt->execute();
            $stmt->bind_result($batting_team, $toss_winner);
            $stmt->fetch();
            $stmt->close();

            if ($batting_team && $toss_winner) {
                // Insert data into tbl_match_data
                $insert_query = "INSERT INTO tbl_match_data (season_id, series_id, match_no, team_1, team_2, batting_team, toss_winner, player_name, upload_path)
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_query);

                if ($insert_stmt) {
                    $insert_stmt->bind_param("iiiiissss", $season_id, $series_id, $match_no, $team_1, $team_2, $batting_team, $toss_winner, $player_name, $target_file);

                    foreach ($players as $player_id) {
                        // Fetch player name from tbl_player
                        $player_query = "SELECT name FROM tbl_player WHERE id = ?";
                        $player_stmt = $conn->prepare($player_query);

                        if ($player_stmt) {
                            $player_stmt->bind_param("i", $player_id);
                            $player_stmt->execute();
                            $player_stmt->bind_result($player_name);
                            $player_stmt->fetch();
                            $player_stmt->close();

                            // Insert each player with the file path
                            $insert_stmt->execute();
                        }
                    }
                    $insert_stmt->close();

                    echo "Data inserted successfully!";
                } else {
                    echo "Error in insert query: " . $conn->error;
                }
            } else {
                echo "No data found for the selected match.";
            }
        } else {
            echo "Error in fetch query: " . $conn->error;
        }
    } else {
        echo "Please fill all required fields and upload a file.";
    }
}
?>

<?php include 'dbconnection.php' ?>

<?php include 'header.php' ?>

<main id="main" class="main">

    <div class="pagetitle">
     
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
    <form method="post" enctype="multipart/form-data">
        <label for="season_id">Season ID:</label>
        <select name="season_id" id="season_id" class="form-control my-2" required>
            <option value="">Select Season</option>
            <?= fetchScheduleOptions($conn, 'season_id') ?>
        </select>

        <label for="series_id">Series ID:</label>
        <select name="series_id" id="series_id" class="form-control my-2" required>
            <option value="">Select Series</option>
            <?= fetchScheduleOptions($conn, 'series_id') ?>
        </select>

        <label for="match_no">Match No:</label>
        <select name="match_no" id="match_no" class="form-control my-2" required>
            <option value="">Select Match</option>
            <?= fetchScheduleOptions($conn, 'match_no') ?>
        </select>

        <label for="team_1">Team 1:</label>
        <select name="team_1" id="team_1" class="form-control my-2" required>
            <option value="">Select Team 1</option>
            <?= fetchScheduleOptions($conn, 'team_1') ?>
        </select>

        <label for="team_2">Team 2:</label>
        <select name="team_2" id="team_2" class="form-control my-2" required>
            <option value="">Select Team 2</option>
            <?= fetchScheduleOptions($conn, 'team_2') ?>
        </select>

        <label>Players:</label><br>
        <?= fetchPlayerOptions($conn) ?>

        <label for="upload_path">Upload File:</label>
        <input type="file" name="upload_path" id="upload_path" class="my-2" required>
<br>
        <input type="submit" value="Submit" class="btn btn-success my-2 col-sm-2">
    </form>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
</div>
    </section>

  </main><!-- End #main -->
</body>

<?php include 'footer.php' ?> 