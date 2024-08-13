<?php
include '../db.php';

// Fetch all homes
$sql = "SELECT * FROM home";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Homes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Manage Homes</h1>
    <a href="add_home.php">Add New Home</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Center Name</th>
                <th>Address</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($home = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $home['home_id']; ?></td>
                <td><?= $home['center_name']; ?></td>
                <td><?= $home['address']; ?></td>
                <td><?= $home['email']; ?></td>
                <td><?= $home['contact']; ?></td>
                <td>
                    <a href="edit_home.php?home_id=<?= $home['home_id']; ?>">Edit</a> |
                    <a href="delete_home.php?home_id=<?= $home['home_id']; ?>" onclick="return confirm('Are you sure you want to delete this home?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
