<?php
include 'db.php';

// Execute the query and handle any errors
$sql = "SELECT * FROM announcements";
$result = $conn->query($sql);

if (!$result) {
    die("Error retrieving announcements: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Announcements</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Announcements</h1>
    <a href="add_announcement.php">Add New</a>
    <table>
        <thead>
            <tr>
                <th>Post Date</th>
                <th>Heading</th>
                <th>Body</th>
                <th>Photo</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['post_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['announce_heading']); ?></td>
                        <td><?php echo htmlspecialchars($row['announce_body']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['announce_pic']); ?>" alt="Announcement Pic" width="100"></td>
                        <td>
                            <a href="edit_announcement.php?id=<?php echo $row['announcements_id']; ?>">Edit</a>
                            <a href="delete_announcement.php?id=<?php echo $row['announcements_id']; ?>" onclick="return confirm('Are you sure you want to delete this announcement?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No announcements found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
