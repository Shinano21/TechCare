<?php
include 'db.php';

$announcement_id = $_GET['id'];

$sql = "SELECT * FROM announcements WHERE announcements_id = $announcement_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No announcement found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $announce_type = $_POST['announce_type'];
    $announce_heading = $_POST['announce_heading'];
    $announce_body = $_POST['announce_body'];
    $post_date = $_POST['post_date'];

    // Handle the image upload
    if (isset($_FILES['announce_pic']) && $_FILES['announce_pic']['error'] == 0) {
        $target_dir = "uploads/";
        $announce_pic = $target_dir . basename($_FILES['announce_pic']['name']);
        move_uploaded_file($_FILES['announce_pic']['tmp_name'], $announce_pic);
    } else {
        // Keep the existing image if no new one is uploaded
        $announce_pic = $row['announce_pic'];
    }

    $sql = "UPDATE announcements 
            SET announce_type='$announce_type', announce_heading='$announce_heading', 
                announce_body='$announce_body', announce_pic='$announce_pic', post_date='$post_date'
            WHERE announcements_id = $announcement_id";

    if ($conn->query($sql) === TRUE) {
        echo "Announcement updated successfully.";
    } else {
        echo "Error updating announcement: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Announcement</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Edit Announcement</h1>
    <form action="edit_announcement.php?id=<?php echo $announcement_id; ?>" method="post" enctype="multipart/form-data">
        <label for="announce_type">Announcement Type:</label><br>
        <input type="text" id="announce_type" name="announce_type" value="<?php echo $row['announce_type']; ?>" required><br><br>

        <label for="announce_heading">Announcement Heading:</label><br>
        <input type="text" id="announce_heading" name="announce_heading" value="<?php echo $row['announce_heading']; ?>" required><br><br>

        <label for="announce_body">Announcement Body:</label><br>
        <textarea id="announce_body" name="announce_body" required><?php echo $row['announce_body']; ?></textarea><br><br>

        <label for="post_date">Post Date:</label><br>
        <input type="date" id="post_date" name="post_date" value="<?php echo $row['post_date']; ?>" required><br><br>

        <label for="announce_pic">Upload New Photo (optional):</label><br>
        <input type="file" id="announce_pic" name="announce_pic"><br><br>

        <input type="submit" value="Update Announcement">
    </form>
    <br>
    <a href="manage_announcements.php">Back to Announcements</a>
</body>
</html>
