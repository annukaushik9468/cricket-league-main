<?php
include 'dbconnection.php';

if (isset($_POST['player_id']) && isset($_POST['team'])) {
    $player_id = mysqli_real_escape_string($con, $_POST['player_id']);
    $team = mysqli_real_escape_string($con, $_POST['team']);
    
    if ($team == 1) {
        // Remove from Team 1
        $query = "DELETE FROM tbl_teamselection WHERE player_team1 = ?";
    } else {
        // Remove from Team 2
        $query = "DELETE FROM tbl_teamselection WHERE player_team2 = ?";
    }

    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $player_id);
    if ($stmt->execute()) {
        echo "Player unselected successfully.";
    } else {
        echo "Error unselecting player: " . $stmt->error;
    }
    $stmt->close();
}
?>
