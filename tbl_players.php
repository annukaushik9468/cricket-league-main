<?php
include 'dbconnection.php';
?>

<style>
        .playerTable {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>

<?php
include 'header.php';
?>


  
    <section class="section dashboard">
      <div class="row">

      <h2>Fetch Players</h2>
    <form id="fetchForm">
        <!-- <label for="season">Season:</label> -->
        <select name="season_id" id="season" class="form-control mt-3">
            <option value="">Select Season</option>
            <!-- Populate with PHP -->
            <?php
            // Database connection
            $conn = new mysqli("localhost", "root", "", "cric_stats");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $seasons = $conn->query("SELECT DISTINCT season_id FROM tbl_teamplayer");
            while($row = $seasons->fetch_assoc()):
            ?>
                <option value="<?php echo $row['season_id']; ?>"><?php echo $row['season_id']; ?></option>
            <?php endwhile; ?>
        </select>
        

        <!-- <label for="series">Series:</label> -->
        <select name="series_id" id="series" class="form-control mt-3">
            <option value="">Select Series</option>
            <!-- Populate with PHP -->
            <?php
            $series = $conn->query("SELECT DISTINCT series_id FROM tbl_teamplayer");
            while($row = $series->fetch_assoc()):
            ?>
                <option value="<?php echo $row['series_id']; ?>"><?php echo $row['series_id']; ?></option>
            <?php endwhile; ?>
        </select>
        

        <!-- <label for="team">Team:</label> -->
        <select name="team_id" id="team" class="form-control mt-3">
            <option value="">Select Team</option>
            <!-- Populate with PHP -->
            <?php
            $teams = $conn->query("SELECT DISTINCT team_id FROM tbl_teamplayer");
            while($row = $teams->fetch_assoc()):
            ?>
                <option value="<?php echo $row['team_id']; ?>"><?php echo $row['team_id']; ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="col-sm-2 mt-3 btn btn-primary">Submit</button>
    </form>


    <div id="playerList">
    <table id="playerTable">
        <thead>
            <tr>
                <th>Player Name</th>
                <th>Document</th>
                <th>Status</th>
                <th>Status Report</th>
                <th>Position</th>
                <th>View Page</th>
            </tr>
        </thead>
            <tbody>
            <!-- Data will be appended here -->
        </tbody>
    </table>
<script>
        $(document).ready(function() {
            // Fetch players based on dropdown selections
            $('#fetchForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'tbl_fetch_players.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        var players = response;
                        var playerTable = $('#playerTable tbody');
                        playerTable.empty();
                        if (players.length > 0) {
                            players.forEach(function(player) {
                                var row = '<tr>' +
                                    '<td>' + player.player_name + '</td>' +
                                    '<td>' +
                                        '<input type="file" class="file-input" data-id="' + player.id + '">' +
                                        '<button class="upload-btn" data-id="' + player.id + '">Upload</button>' +
                                    '</td>' +
                                    '<td class="status">' +
                                        '<span class="status-text" data-id="' + player.id + '">' + player.status + '</span>' +
                                        '<img class="status-img" data-id="' + player.id + '" src="pending.png">' + // Use appropriate image paths
                                    '</td>' +
                                    '<td>' +
                                        '<select class="position-select" data-id="' + player.id + '">' +
                                            '<option value="">Select Position</option>' +
                                            '<option value="1">1</option>' +
                                            '<option value="2">2</option>' +
                                            '<option value="3">3</option>' +
                                            '<option value="4">4</option>' +
                                            '<option value="5">5</option>' +
                                            '<option value="6">6</option>' +
                                            '<option value="7">7</option>' +
                                            '<option value="8">8</option>' +
                                            '<option value="9">9</option>' +
                                            '<option value="10">10</option>' +
                                        '</select>' +
                                    '</td>' +
                                    '<td>' +
                                        '<button class="save-btn" data-id="' + player.id + '">Save</button>' +
                                    '</td>' +
                                '</tr>';
                                playerTable.append(row);
                            });
                        } else {
                            playerTable.append('<tr><td colspan="5">No players found</td></tr>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching players:', error);
                    }
                });
            });

            // Handle file upload
            $(document).on('click', '.upload-btn', function() {
                var player_id = $(this).data('id');
                var fileInput = $('.file-input[data-id="' + player_id + '"]')[0];
                var formData = new FormData();
                formData.append('file', fileInput.files[0]);
                formData.append('player_id', player_id);

                $.ajax({
                    url: 'upload_file.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        var statusSpan = $('span.status-text[data-id="' + player_id + '"]');
                        var statusImg = $('img.status-img[data-id="' + player_id + '"]');
                        if (result.success) {
                            statusSpan.text('Uploaded');
                            statusImg.attr('src', 'right-tick.png'); // Path to right tick image
                        } else {
                            statusSpan.text('Failed');
                            statusImg.attr('src', 'wrong-tick.png'); // Path to wrong tick image
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error uploading file:', error);
                    }
                });
            });

            // Handle save button click
            $(document).on('click', '.save-btn', function() {
                var player_id = $(this).data('id');
                var position = $('.position-select[data-id="' + player_id + '"]').val();
                if (position) {
                    $.ajax({
                        url: 'tbl_fetch_positions.php',
                        type: 'POST',
                        data: {
                            player_id: player_id,
                            position: position
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('Position saved successfully');
                            } else {
                                alert('Failed to save position');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error saving position:', error);
                        }
                    });
                } else {
                    alert('Please select a position');
                }
            });
        });
    </script>
       
<?php
include 'footer.php';
?>