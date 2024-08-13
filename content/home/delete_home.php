<?php
include '../db.php'; // Include your database connection

// Check if home_id is provided
$home_id = isset($_GET['home_id']) ? intval($_GET['home_id']) : 0;

if ($home_id) {
    // Delete data from the home table
    $sql = "DELETE FROM home WHERE home_id = '$home_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Home deleted successfully. <a href='manage_homes.php'>Go back to management</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid home ID.";
}

$conn->close();
?>
