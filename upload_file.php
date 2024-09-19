<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "cric_stats");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_FILES['file']) && isset($_POST['player_id'])) {
    $player_id = intval($_POST['player_id']);
    $filename = $_FILES['file']['name'];
    $tempName = $_FILES['file']['tmp_name'];
    $uploadDir = 'uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadPath = $uploadDir . $filename;
    if (move_uploaded_file($tempName, $uploadPath)) {
        $updateQuery = "UPDATE tbl_teamplayer SET upload_path = ?, status = 'Uploaded' WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $uploadPath, $player_id);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["success" => true, "player_id" => $player_id]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to upload file"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "No file uploaded or player_id missing"]);
}

$conn->close();
?>
