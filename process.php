<?php
// Database connection
$host = 'localhost';
$dbname = 'cric_stats';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle file upload
if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['csv_file']['tmp_name'];
    
    // Open and read CSV file
    if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {
        fgetcsv($handle); // Skip header row if needed

        // Prepare SQL for insertion
        $stmt = $pdo->prepare("
            INSERT INTO tbl_team_record (season_id, series_id, team_1, team_2,match_no,battsman,bowler dot_ball, one_run, two_run, three_run, four_run, six_run, wide, wicket)
            VALUES (:season_id, :series_id, :team_1, :team_2, :match_no,:battsman,:bowler,:dot_ball, :one_run, :two_run, :three_run, :four_run, :six_run, :wide, :wicket)
        ");

        while (($data = fgetcsv($handle)) !== FALSE) {
            // Assume CSV columns: dot_ball, one_run, two_run, three_run, four_run, six_run, wide, wicket
            $stmt->execute([
                ':season_id' => $_POST['season_id'],
                ':series_id' => $_POST['series_id'],
                ':team_1' => $_POST['team_1'],
                ':team_2' => $_POST['team_2'],
':match_no' => $data[0],
':battsman' => $data[1],
':bowler' => $data[2],
                ':dot_ball' => $data[3],
                ':one_run' => $data[4],
                ':two_run' => $data[5],
                ':three_run' => $data[6],
                ':four_run' => $data[7],
                ':six_run' => $data[8],
                ':wide' => $data[9],
                ':wicket' => $data[10]
            ]);
        }

        fclose($handle);
        echo "Data imported successfully!";
    } else {
        echo "Error opening CSV file.";
    }
} else {
    echo "No file uploaded or upload error.";
}
?>
