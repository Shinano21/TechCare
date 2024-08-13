<?php
include '../db.php'; // Include your database connection

// Check if home_id is provided
$home_id = isset($_GET['home_id']) ? intval($_GET['home_id']) : 0;

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

    // Update data in the home table
    $sql = "UPDATE home SET center_name='$center_name', address='$address', email='$email', contact='$contact', open_hours='$open_hours', 
            bg_img='$bg_img', short_desc='$short_desc', goal='$goal', section_pic='$section_pic', contact_mess='$contact_mess', 
            office_hrs='$office_hrs', programs_pic='$programs_pic', announce_pic='$announce_pic' WHERE home_id='$home_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Home updated successfully. <a href='manage_homes.php'>Go back to management</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

// Fetch current data
$sql = "SELECT * FROM home WHERE home_id = '$home_id'";
$result = $conn->query($sql);
$home = $result->fetch_assoc();

if (!$home) {
    echo "Home not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Edit Home</h1>
    <form method="post" action="edit_home.php?home_id=<?= $home_id; ?>">
        <label for="center_name">Center Name:</label><br>
        <input type="text" id="center_name" name="center_name" value="<?= htmlspecialchars($home['center_name']); ?>" required><br><br>

        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address" value="<?= htmlspecialchars($home['address']); ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($home['email']); ?>" required><br><br>

        <label for="contact">Contact:</label><br>
        <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($home['contact']); ?>" required><br><br>

        <label for="open_hours">Open Hours:</label><br>
        <input type="text" id="open_hours" name="open_hours" value="<?= htmlspecialchars($home['open_hours']); ?>"><br><br>

        <label for="bg_img">Background Image:</label><br>
        <input type="text" id="bg_img" name="bg_img" value="<?= htmlspecialchars($home['bg_img']); ?>"><br><br>

        <label for="short_desc">Short Description:</label><br>
        <textarea id="short_desc" name="short_desc"><?= htmlspecialchars($home['short_desc']); ?></textarea><br><br>

        <label for="goal">Goal:</label><br>
        <textarea id="goal" name="goal"><?= htmlspecialchars($home['goal']); ?></textarea><br><br>

        <label for="section_pic">Section Picture:</label><br>
        <input type="text" id="section_pic" name="section_pic" value="<?= htmlspecialchars($home['section_pic']); ?>"><br><br>

        <label for="contact_mess">Contact Message:</label><br>
        <textarea id="contact_mess" name="contact_mess"><?= htmlspecialchars($home['contact_mess']); ?></textarea><br><br>

        <label for="office_hrs">Office Hours:</label><br>
        <input type="text" id="office_hrs" name="office_hrs" value="<?= htmlspecialchars($home['office_hrs']); ?>"><br><br>

        <label for="programs_pic">Programs Picture:</label><br>
        <input type="text" id="programs_pic" name="programs_pic" value="<?= htmlspecialchars($home['programs_pic']); ?>"><br><br>

        <label for="announce_pic">Announcement Picture:</label><br>
        <input type="text" id="announce_pic" name="announce_pic" value="<?= htmlspecialchars($home['announce_pic']); ?>"><br><br>

        <input type="submit" value="Update Home">
    </form>
    <a href="manage_homes.php">Back to Management</a>
</body>
</html>
