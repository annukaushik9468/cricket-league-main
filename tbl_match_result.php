<?php include 'dbconnection.php'; ?>
<?php include 'header.php'; ?>
<style type="text/css">
    /* General Body Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f7fa;
    margin: 0;
    padding: 0;
}

/* Header Styling */
header {
    background-color: #4CAF50;
    color: white;
    padding: 15px 0;
    text-align: center;
    font-size: 24px;
}

/* Section Styling */
section.dashboard {
    padding: 20px;
}

/* Form Styling */
form {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: 0 auto;
}

/* Form Input Styling */
label {
    font-weight: bold;
    margin-bottom: 8px;
    display: inline-block;
}

select, input[type="radio"], button {
    margin: 8px 0;
    padding: 8px;
    width: 100%;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

select {
    width: 100%;
}

input[type="radio"] {
    width: auto;
}

button {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 18px;
    padding: 10px;
    border-radius: 5px;
}

button:hover {
    background-color: #45a049;
}

/* Radio Button Styling */
label input[type="radio"] {
    margin-right: 10px;
}

input[type="radio"]:checked + label {
    font-weight: bold;
}

/* Dropdown Styling */
select.form-control {
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 10px;
}

select.form-control:focus {
    outline: none;
    border-color: #4CAF50;
}

/* Form Field Section Styling */
section .row {
    display: flex;
    flex-direction: column;
}

/* Success Message Styling */
p {
    color: green;
    font-weight: bold;
}

/* Error Message Styling */
.error {
    color: red;
    font-weight: bold;
}


</style>
    <section class="section dashboard">
        <div class="row">
            <?php
            $conn = new mysqli("localhost", "root", "", "cric_stats");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch seasons and series for dropdowns
            $seasons = $conn->query("SELECT id, title FROM tbl_season");
            $series = $conn->query("SELECT id, title FROM tbl_series");

            // Handle form submission
           if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch the form data
    $season_id = $_POST['season_id'];
    $series_id = $_POST['series_id'];
    $team_1 = $_POST['team_1'];
    $team_2 = $_POST['team_2'];
    $toss_winner = $_POST['toss_winner'];
    $toss_loser = $_POST['toss_loser'];
    $batting_team = $_POST['batting_team'];
    $fielding_team = $_POST['fielding_team'];
    $winner_team = $_POST['winner_team'];
    $loser_team = $_POST['loser_team'];
    $match_result = $_POST['match_result']; // Added this line to fetch match result

    // Insert data into tbl_matches
    $stmt = $conn->prepare("INSERT INTO tbl_matches (season_id, series_id, team_1, team_2, toss_winner, toss_loser, batting_team, fielding_team, winner_team, loser_team, match_result) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiiisssss", $season_id, $series_id, $team_1, $team_2, $toss_winner, $toss_loser, $batting_team, $fielding_team, $winner_team, $loser_team, $match_result);

    if ($stmt->execute()) {
        echo "<p>Match saved successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    // Close the statement
    $stmt->close();
}

            ?>
            <html lang="en">
            <head>
                <title>Toss and Match Result Entry</title>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    function fetchTeamsBySeries() {
                        var seriesId = document.getElementById("series_id").value;

                        $.ajax({
                            url: "fetch_team.php",
                            method: "POST",
                            data: { series_id: seriesId },
                            success: function(data) {
                                var teams = JSON.parse(data);

                                // Update team_1, team_2, toss_winner, and batting_team dropdowns
                                var team1Dropdown = document.getElementById("team_1");
                                var team2Dropdown = document.getElementById("team_2");
                                var tossWinnerRadios = document.getElementById("toss_winner");
                                var battingTeamRadios = document.getElementById("batting_team");
                                var winnerDropdown = document.getElementById("winner_team");
                                var loserDropdown = document.getElementById("loser_team");

                                var defaultOption = '<option value="">-- Select --</option>';
                                
                                team1Dropdown.innerHTML = defaultOption;
                                team2Dropdown.innerHTML = defaultOption;
                                tossWinnerRadios.innerHTML = '';
                                battingTeamRadios.innerHTML = '';
                                winnerDropdown.innerHTML = defaultOption;
                                loserDropdown.innerHTML = defaultOption;

                                teams.forEach(function(team) {
                                    var option = `<option value="${team.id}">${team.title}</option>`;
                                    team1Dropdown.innerHTML += option;
                                    team2Dropdown.innerHTML += option;

                                    var radio = `<label>
                                        <input type="radio" name="toss_winner" value="${team.id}" onclick="setTossLoser(${team.id})"> ${team.title}
                                    </label>`;
                                    tossWinnerRadios.innerHTML += radio;

                                    var battingRadio = `<label>
                                        <input type="radio" name="batting_team" value="${team.id}" onclick="setFieldingTeam(${team.id})"> ${team.title}
                                    </label>`;
                                    battingTeamRadios.innerHTML += battingRadio;
                                });

                                // Populate winner_team and loser_team based on series
                                teams.forEach(function(team) {
                                    var winnerOption = `<option value="${team.id}">${team.title}</option>`;
                                    winnerDropdown.innerHTML += winnerOption;
                                    loserDropdown.innerHTML += winnerOption;
                                });
                            }
                        });
                    }

                    function setTossLoser(selectedTeamId) {
                        var team1 = document.getElementById("team_1").value;
                        var team2 = document.getElementById("team_2").value;

                        if (team1 == selectedTeamId) {
                            document.getElementById("toss_loser").innerHTML = `<label>
                                <input type="radio" name="toss_loser" value="${team2}" checked> Team 2
                            </label>`;
                        } else if (team2 == selectedTeamId) {
                            document.getElementById("toss_loser").innerHTML = `<label>
                                <input type="radio" name="toss_loser" value="${team1}" checked> Team 1
                            </label>`;
                        }
                    }

                    function setFieldingTeam(selectedTeamId) {
                        var team1 = document.getElementById("team_1").value;
                        var team2 = document.getElementById("team_2").value;

                        if (team1 == selectedTeamId) {
                            document.getElementById("fielding_team").innerHTML = `<label>
                                <input type="radio" name="fielding_team" value="${team2}" checked> Team 2
                            </label>`;
                        } else if (team2 == selectedTeamId) {
                            document.getElementById("fielding_team").innerHTML = `<label>
                                <input type="radio" name="fielding_team" value="${team1}" checked> Team 1
                            </label>`;
                        }
                    }

                    function setWinnerLoser() {
                        var winnerTeam = document.getElementById("winner_team").value;
                        var team1 = document.getElementById("team_1").value;
                        var team2 = document.getElementById("team_2").value;

                        // Set loser team automatically based on winner selection
                        if (winnerTeam == team1) {
                            document.getElementById("loser_team").value = team2;
                        } else if (winnerTeam == team2) {
                            document.getElementById("loser_team").value = team1;
                        }
                    }

                    function updateTeamSelection() {
                        var team1 = document.getElementById("team_1").value;
                        var team2 = document.getElementById("team_2").value;

                        var team1Options = document.getElementById("team_1").options;
                        var team2Options = document.getElementById("team_2").options;

                        // Hide selected team from the other dropdown
                        for (var i = 0; i < team1Options.length; i++) {
                            team2Options[i].style.display = (team1 == team2Options[i].value || team2 == team2Options[i].value) ? 'none' : 'block';
                        }
                        for (var i = 0; i < team2Options.length; i++) {
                            team1Options[i].style.display = (team2 == team1Options[i].value || team1 == team1Options[i].value) ? 'none' : 'block';
                        }
                    }

                    $(document).ready(function() {
                        // Call the updateTeamSelection whenever a team is selected
                        $("#team_1, #team_2").change(function() {
                            updateTeamSelection();
                        });
                    });
                </script>
       
            </head>
            <body>
                <form method="POST">
                    <label for="season_id">Season ID:</label>
                    <select id="season_id" name="season_id" class="form-control" required>
                        <?php while ($row = $seasons->fetch_assoc()) { ?>
                            <option value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
                        <?php } ?>
                    </select>

                    <label for="series_id">Series ID:</label>
                    <select id="series_id" name="series_id" class="form-control" required onchange="fetchTeamsBySeries()">
                        <option value="">-- Select Series --</option>
                        <?php while ($row = $series->fetch_assoc()) { ?>
                            <option value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
                        <?php } ?>
                    </select>

                    <label for="team_1">Team 1:</label>
                    <select id="team_1" name="team_1" class="form-control" required>
                        <option value="">-- Select Team 1 --</option>
                    </select>

                    <label for="team_2">Team 2:</label>
                    <select id="team_2" name="team_2" class="form-control" required>
                        <option value="">-- Select Team 2 --</option>
                    </select>

                    <label for="toss_winner">Toss Winner:</label>
                    <div id="toss_winner"></div>

                    <label for="toss_loser">Toss Loser:</label>
                    <div id="toss_loser"></div>

                    <label for="batting_team">Batting Team:</label>
                    <div id="batting_team"></div>

                    <label for="fielding_team">Fielding Team:</label>
                    <div id="fielding_team"></div>

<label for="match_result">Match Result:</label>
<label>
    <input type="radio" name="match_result" value="win"> Win
</label>
<label>
    <input type="radio" name="match_result" value="loss"> Loss
</label>
<label>
    <input type="radio" name="match_result" value="tie"> Tie
</label>
<label>
    <input type="radio" name="match_result" value="draw"> Draw
</label>
<label>
    <input type="radio" name="match_result" value="abandoned"> Abandoned
</label>


                    <label for="winner_team">Winner Team:</label>
                    <select id="winner_team" name="winner_team" class="form-control" required onchange="setWinnerLoser()">
                        <option value="">-- Select Winner --</option>
                    </select>

                    <label for="loser_team">Loser Team:</label>
                    <select id="loser_team" name="loser_team" class="form-control" required>
                        <option value="">-- Select Loser --</option>
                    </select>

                    <button type="submit" class="col-md-2 btn btn-success my-2">Save Match</button>
                </form>
            </body>
            </html>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
