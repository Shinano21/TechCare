<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare SQL query
    $sql = "SELECT profile_pic FROM residents WHERE resident_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($imagePath);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    if ($imagePath && file_exists($imagePath)) {
        $imageType = mime_content_type($imagePath); // Get the correct MIME type
        header("Content-Type: $imageType");
        readfile($imagePath); // Output the image content
    } else {
        echo "No image found.";
    }
} else {
    echo "No ID provided.";
}

?>
