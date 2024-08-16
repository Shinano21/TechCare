<?php
include 'db.php'; // Include your database connection file

// Fetch residents for the dropdown
$residents = [];
$sql = "SELECT resident_id, CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name FROM residents";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $residents[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resident_id = $_POST['resident_id'];
    $nutrition_type = $_POST['nutrition_type'];
    $supplements = $_POST['supplements'];
    $date_assessed = $_POST['date_assessed'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    // Insert query
    $sql = "INSERT INTO nutrition (resident_id, nutrition_type, supplements, date_assessed, height, weight, status, remarks)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssddss", $resident_id, $nutrition_type, $supplements, $date_assessed, $height, $weight, $status, $remarks);

    if ($stmt->execute()) {
        echo "Record added successfully!";
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
</head>
<body>
    <h2>Add Nutrition Record</h2>
    <form method="POST" action="">
        <label for="resident_id">Resident:</label>
        <select id="resident_id" name="resident_id" required>
            <option value="">Select Resident</option>
            <?php foreach ($residents as $resident): ?>
                <option value="<?php echo $resident['resident_id']; ?>">
                    <?php echo $resident['full_name']; ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="nutrition_type">Nutrition Type:</label>
        <input type="text" id="nutrition_type" name="nutrition_type" required><br>

        <label for="supplements">Supplements:</label>
        <textarea id="supplements" name="supplements"></textarea><br>

        <label for="date_assessed">Date Assessed:</label>
        <input type="date" id="date_assessed" name="date_assessed" required><br>

        <label for="height">Height (cm):</label>
        <input type="number" step="0.01" id="height" name="height" required><br>

        <label for="weight">Weight (kg):</label>
        <input type="number" step="0.01" id="weight" name="weight" required><br>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status"><br>

        <label for="remarks">Remarks:</label>
        <textarea id="remarks" name="remarks"></textarea><br>

        <input type="submit" value="Add Record">
    </form>
</body>
</html>
