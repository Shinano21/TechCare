<?php
include 'db.php'; // Include the database connection

if (isset($_GET['anibite_id'])) {
    $anibite_id = $_GET['anibite_id'];

    // Prepare the SQL statement to delete the record
    $stmt = $conn->prepare("DELETE FROM animal_bite WHERE anibite_id = ?");
    $stmt->bind_param("i", $anibite_id); // Bind the parameter as an integer

    if ($stmt->execute()) {
        // Redirect back to the dashboard with a success message
        header("Location: dashboard.php?message=Record deleted successfully");
        exit();
    } else {
        // Error handling
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the statement
} else {
    echo "Invalid request.";
}

$conn->close(); // Close the database connection
?>
