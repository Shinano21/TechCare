
<?php
include('db.php');

if (isset($_POST['resident_id'])) {
    $resident_id = $_POST['resident_id'];

    $sql = "SELECT first_name, middle_name, last_name, suffix_name, gender, date_of_birth, place_of_birth, religion, citizenship, street, zone, barangay, municipal, province, contact_number, education, occupation, civil_status FROM residents WHERE resident_id = $resident_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Resident not found']);
    }
}
?>
