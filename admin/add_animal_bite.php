<?php
include 'db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resident_id = $_POST['resident_id'];
    $date_of_exposure = $_POST['date_of_exposure'];
    $nature_of_bite = $_POST['nature_of_bite'];
    $category_of_bite = $_POST['category_of_bite'];
    $actions_taken = $_POST['actions_taken'];

    // Insert data into the table
    $sql = "INSERT INTO animal_bite (resident_id, date_of_exposure, nature_of_bite, category_of_bite, actions_taken) 
            VALUES ('$resident_id', '$date_of_exposure', '$nature_of_bite', '$category_of_bite', '$actions_taken')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!-- HTML Form for Adding Data -->
<form method="POST" action="add_animal_bite.php">
    Resident ID: <input type="number" name="resident_id" required><br>
    Date of Exposure: <input type="date" name="date_of_exposure" required><br>
    Nature of Bite: <input type="text" name="nature_of_bite" required><br>
    Category of Bite: <input type="text" name="category_of_bite" required><br>
    Actions Taken: <textarea name="actions_taken" required></textarea><br>
    <button type="submit">Add Record</button>
</form>
