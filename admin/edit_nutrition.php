<?php
include 'db_connection.php';

// Fetch all residents for the dropdown
$residents = [];
$resident_sql = "SELECT resident_id, CONCAT(first_name, ' ', middle_name, ' ', last_name, ' (ID: ', resident_id, ')') AS full_name FROM residents";
$resident_result = $conn->query($resident_sql);
if ($resident_result->num_rows > 0) {
    while ($row = $resident_result->fetch_assoc()) {
        $residents[] = $row;
    }
}

// Fetch the record to be edited
if (isset($_GET['id'])) {
    $nutrition_id = $_GET['id'];
    $sql = "SELECT * FROM nutrition WHERE nutrition_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $nutrition_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $nutrition = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission for updating the record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nutrition_id = $_POST['nutrition_id'];
    $resident_id = $_POST['resident_id'];
    $nutrition_type = $_POST['nutrition_type'];
    $supplements = $_POST['supplements'];
    $date_assessed = $_POST['date_assessed'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    // Update record
    $sql = "UPDATE nutrition SET resident_id=?, nutrition_type=?, supplements=?, date_assessed=?, height=?, weight=?, status=?, remarks=? WHERE nutrition_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssddssi", $resident_id, $nutrition_type, $supplements, $date_assessed, $height, $weight, $status, $remarks, $nutrition_id);

    if ($stmt->execute()) {
        echo "Record updated successfully!";
        header("Location: nutrition_table.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nutrition Record</title>
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <h2>Edit Nutrition Record</h2>
    <form method="POST" action="">
        <input type="hidden" name="nutrition_id" value="<?php echo $nutrition['nutrition_id']; ?>">

        <label for="resident_id">Resident (Type Name or ID):</label>
        <select id="resident_id" name="resident_id" class="select2" required>
            <?php foreach ($residents as $resident): ?>
                <option value="<?php echo $resident['resident_id']; ?>" <?php if ($resident['resident_id'] == $nutrition['resident_id']) echo 'selected'; ?>>
                    <?php echo $resident['full_name']; ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="nutrition_type">Nutrition Type:</label>
        <input type="text" id="nutrition_type" name="nutrition_type" value="<?php echo $nutrition['nutrition_type']; ?>" required><br><br>

        <label for="supplements">Supplements:</label>
        <textarea id="supplements" name="supplements"><?php echo $nutrition['supplements']; ?></textarea><br><br>

        <label for="date_assessed">Date Assessed:</label>
        <input type="date" id="date_assessed" name="date_assessed" value="<?php echo $nutrition['date_assessed']; ?>" required><br><br>

        <label for="height">Height (cm):</label>
        <input type="number" step="0.01" id="height" name="height" value="<?php echo $nutrition['height']; ?>" required><br><br>

        <label for="weight">Weight (kg):</label>
        <input type="number" step="0.01" id="weight" name="weight" value="<?php echo $nutrition['weight']; ?>" required><br><br>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status" value="<?php echo $nutrition['status']; ?>"><br><br>

        <label for="remarks">Remarks:</label>
        <textarea id="remarks" name="remarks"><?php echo $nutrition['remarks']; ?></textarea><br><br>

        <input type="submit" value="Update Record">
    </form>
    <br>
    <a href="nutrition_table.php">Back to Nutrition Records</a>

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Type resident name or ID',
                allowClear: true
            });
        });
    </script>
</body>
</html>
