<?php
include '../db.php'; // Include database connection from the parent directory

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $center_name = $_POST['center_name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $open_hours = $_POST['open_hours'];
    $bg_img = $_POST['bg_img'];
    $short_desc = $_POST['short_desc'];
    $goal = $_POST['goal'];
    $section_pic = $_POST['section_pic'];
    $contact_mess = $_POST['contact_mess'];
    $office_hrs = $_POST['office_hrs'];
    $programs_pic = $_POST['programs_pic'];
    $announce_pic = $_POST['announce_pic'];

    // Insert data into the home table
    $sql = "INSERT INTO home (center_name, address, email, contact, open_hours, bg_img, short_desc, goal, section_pic, contact_mess, office_hrs, programs_pic, announce_pic) 
            VALUES ('$center_name', '$address', '$email', '$contact', '$open_hours', '$bg_img', '$short_desc', '$goal', '$section_pic', '$contact_mess', '$office_hrs', '$programs_pic', '$announce_pic')";

    if ($conn->query($sql) === TRUE) {
        echo "New home added successfully. <a href='manage_homes.php'>Go back to management</a>";
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
    <title>Add Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add New Home</h1>
    <form method="post" action="add_home.php">
        <!-- Form fields here -->
        <input type="submit" value="Add Home">
    </form>
    <a href="manage_homes.php">Back to Management</a>
</body>
</html>
