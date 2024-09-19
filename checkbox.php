<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkbox Form</title>
    <script>
        function updateCheckboxes(checkbox) {
            const checkbox1 = document.getElementById('checkbox1');
            const checkbox2 = document.getElementById('checkbox2');

            if (checkbox.id === 'checkbox1' && checkbox.checked) {
                checkbox2.checked = false;
                checkbox2.value = 'loss';
            } else if (checkbox.id === 'checkbox2' && checkbox.checked) {
                checkbox1.checked = false;
                checkbox1.value = 'loss';
            }

            // Ensure that one checkbox is always checked
            if (!checkbox1.checked && !checkbox2.checked) {
                if (checkbox.id === 'checkbox1') {
                    checkbox2.checked = true;
                } else {
                    checkbox1.checked = true;
                }
            }
        }
    </script>
</head>
<body>
    <form method="POST" action="submit.php">
        <label for="checkbox1">Checkbox 1 (Win)</label>
        <input type="checkbox" id="checkbox1" name="checkbox1" value="win" onclick="updateCheckboxes(this)"><br>
        <label for="checkbox2">Checkbox 2 (Loss)</label>
        <input type="checkbox" id="checkbox2" name="checkbox2" value="loss" onclick="updateCheckboxes(this)"><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
