<?php
include 'db.php'; // Include the database connection

if (isset($_GET['anibite_id'])) {
    $anibite_id = $_GET['anibite_id'];

    // Fetch the existing record
    $sql = "SELECT * FROM animal_bite WHERE anibite_id = $anibite_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $anibite_id = $_POST['anibite_id'];
    $resident_id = $_POST['resident_id'];
    $date_of_exposure = $_POST['date_of_exposure'];
    $nature_of_bite = $_POST['nature_of_bite'];
    $category_of_bite = $_POST['category_of_bite'];
    $actions_taken = $_POST['actions_taken'];

    // Update the record in the database
    $sql = "UPDATE animal_bite SET 
            resident_id = '$resident_id', 
            date_of_exposure = '$date_of_exposure', 
            nature_of_bite = '$nature_of_bite', 
            category_of_bite = '$category_of_bite', 
            actions_taken = '$actions_taken' 
            WHERE anibite_id = $anibite_id";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!-- HTML Form for Editing Data -->
<form method="POST" action="edit_animal_bite.php">
    <input type="hidden" name="anibite_id" value="<?php echo $row['anibite_id']; ?>">
    Resident ID: <input type="number" name="resident_id" value="<?php echo $row['resident_id']; ?>" required><br>
    Date of Exposure: <input type="date" name="date_of_exposure" value="<?php echo $row['date_of_exposure']; ?>" required><br>
    Nature of Bite: <input type="text" name="nature_of_bite" value="<?php echo $row['nature_of_bite']; ?>" required><br>
    Category of Bite: <input type="text" name="category_of_bite" value="<?php echo $row['category_of_bite']; ?>" required><br>
    Actions Taken: <textarea name="actions_taken" required><?php echo $row['actions_taken']; ?></textarea><br>
    <button type="submit">Update Record</button>
</form>
