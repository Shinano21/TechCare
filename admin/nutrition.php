<?php
include 'db.php';

// Handle delete operation
if (isset($_GET['delete'])) {
    $nutrition_id = $_GET['delete'];
    $sql = "DELETE FROM nutrition WHERE nutrition_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $nutrition_id);

    if ($stmt->execute()) {
        echo "Record deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all nutrition records
$sql = "SELECT n.nutrition_id, CONCAT(r.first_name, ' ', r.middle_name, ' ', r.last_name) AS resident_name, n.nutrition_type, n.supplements, n.date_assessed, n.height, n.weight, n.status, n.remarks
        FROM nutrition n
        JOIN residents r ON n.resident_id = r.resident_id";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Records</title>
</head>
<body>
    <h2>Nutrition Records</h2>
    <a href="add_nutrition.php">Add New Record</a><br><br>
    <table border="1">
        <thead>
            <tr>
                <th>Resident</th>
                <th>Nutrition Type</th>
                <th>Supplements</th>
                <th>Date Assessed</th>
                <th>Height (cm)</th>
                <th>Weight (kg)</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['resident_name']; ?></td>
                        <td><?php echo $row['nutrition_type']; ?></td>
                        <td><?php echo $row['supplements']; ?></td>
                        <td><?php echo $row['date_assessed']; ?></td>
                        <td><?php echo $row['height']; ?></td>
                        <td><?php echo $row['weight']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['remarks']; ?></td>
                        <td>
                            <a href="edit_nutrition.php?id=<?php echo $row['nutrition_id']; ?>">Edit</a> |
                            <a href="?delete=<?php echo $row['nutrition_id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
