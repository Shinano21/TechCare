<?php
include 'db.php';

// Fetch all residents for the dropdown
$residents = [];
$resident_sql = "SELECT resident_id, CONCAT(first_name, ' ', middle_name, ' ', last_name, ' (ID: ', resident_id, ')') AS full_name FROM residents";
$resident_result = $conn->query($resident_sql);
if ($resident_result->num_rows > 0) {
    while ($row = $resident_result->fetch_assoc()) {
        $residents[] = $row;
    }
}

// Handle form submission for adding a new record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resident_id = $_POST['resident_id'];
    $nutrition_type = $_POST['nutrition_type'];
    $supplements = $_POST['supplements'];
    $date_assessed = $_POST['date_assessed'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    // Insert new record
    $sql = "INSERT INTO nutrition (resident_id, nutrition_type, supplements, date_assessed, height, weight, status, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssddss", $resident_id, $nutrition_type, $supplements, $date_assessed, $height, $weight, $status, $remarks);

    if ($stmt->execute()) {
        echo "Record added successfully!";
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
    <title>Add Nutrition Record</title>
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h2>Add Nutrition Record</h2>
    <form method="POST" action="">
        <label for="resident_id">Resident (Type Name or ID):</label>
        <select id="resident_id" name="resident_id" class="select2" required>
            <option value="">Select Resident</option>
            <?php foreach ($residents as $resident): ?>
                <option value="<?php echo $resident['resident_id']; ?>">
                    <?php echo $resident['full_name']; ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="nutrition_type">Nutrition Type:</label>
        <input type="text" id="nutrition_type" name="nutrition_type" required><br><br>

        <label for="supplements">Supplements:</label>
        <textarea id="supplements" name="supplements"></textarea><br><br>

        <label for="date_assessed">Date Assessed:</label>
        <input type="date" id="date_assessed" name="date_assessed" required><br><br>

        <label for="height">Height (cm):</label>
        <input type="number" step="0.01" id="height" name="height" required><br><br>

        <label for="weight">Weight (kg):</label>
        <input type="number" step="0.01" id="weight" name="weight" required><br><br>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status"><br><br>

        <label for="remarks">Remarks:</label>
        <textarea id="remarks" name="remarks"></textarea><br><br>

        <input type="submit" value="Add Record">
    </form>
    <br>
    <a href="nutrition.php">Back to Nutrition Records</a>

    <!-- Include Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 with search functionality
            $('#resident_id').select2({
                placeholder: 'Type resident name or ID',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>
</html>
