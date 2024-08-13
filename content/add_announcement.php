<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input data
    $announce_type = $conn->real_escape_string($_POST['announce_type']);
    $announce_heading = $conn->real_escape_string($_POST['announce_heading']);
    $announce_body = $conn->real_escape_string($_POST['announce_body']);
    $post_date = $conn->real_escape_string($_POST['post_date']);

    // Handle the image upload securely
    $announce_pic = '';
    if (isset($_FILES['announce_pic']) && $_FILES['announce_pic']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['announce_pic']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate the image file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['announce_pic']['tmp_name'], $target_file)) {
                $announce_pic = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            exit;
        }
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO announcements (home_id, announce_type, announce_heading, announce_body, announce_pic, post_date) VALUES (?, ?, ?, ?, ?, ?)");
    $home_id = 1; // Replace with the actual home_id if necessary
    $stmt->bind_param('isssss', $home_id, $announce_type, $announce_heading, $announce_body, $announce_pic, $post_date);

    if ($stmt->execute()) {
        echo "New announcement added successfully";
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
    <title>Add New Announcement</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add New Announcement</h1>
    <form action="add_announcement.php" method="post" enctype="multipart/form-data">
        <label for="announce_type">Announcement Type:</label><br>
        <input type="text" id="announce_type" name="announce_type" required><br><br>

        <label for="announce_heading">Announcement Heading:</label><br>
        <input type="text" id="announce_heading" name="announce_heading" required><br><br>

        <label for="announce_body">Announcement Body:</label><br>
        <textarea id="announce_body" name="announce_body" required></textarea><br><br>

        <label for="post_date">Post Date:</label><br>
        <input type="date" id="post_date" name="post_date" required><br><br>

        <label for="announce_pic">Upload Photo:</label><br>
        <input type="file" id="announce_pic" name="announce_pic"><br><br>

        <input type="submit" value="Add Announcement">
    </form>
    <br>
    <a href="manage_announcements.php">Back to Announcements</a>
</body>
</html>
