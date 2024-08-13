<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare SQL query
    $sql = "SELECT profile_pic FROM residents WHERE resident_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($imageBlob);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    if ($imageBlob) {
        header("Content-Type: image/jpeg"); // Adjust the content type based on your image format
        echo $imageBlob;
    } else {
        echo "No image found.";
    }
} else {
    echo "No ID provided.";
}
?>
