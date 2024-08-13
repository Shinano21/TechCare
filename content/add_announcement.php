<?php
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $announce_type = $_POST['announce_type'];
    $announce_heading = $_POST['announce_heading'];
    $announce_body = $_POST['announce_body'];
    $post_date = $_POST['post_date'];

    // Handle file upload
    $announce_pic = null;
    if (isset($_FILES['announce_pic']) && $_FILES['announce_pic']['error'] == 0) {
        $target_dir = "uploads/";
        $announce_pic = $target_dir . basename($_FILES['announce_pic']['name']);

        // Ensure the 'uploads' directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Move the uploaded file to the 'uploads' directory
        if (!move_uploaded_file($_FILES['announce_pic']['tmp_name'], $announce_pic)) {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    // Set the default home_id or fetch dynamically if required
    $home_id = 1; // Make sure this exists in the home table

    // Insert the announcement data into the database
    $sql = "INSERT INTO announcements (home_id, announce_type, announce_heading, announce_body, announce_pic, post_date) 
            VALUES ('$home_id', '$announce_type', '$announce_heading', '$announce_body', '$announce_pic', '$post_date')";

    if ($conn->query($sql) === TRUE) {
        echo "New announcement added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Announcement</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add Announcement</h1>
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
