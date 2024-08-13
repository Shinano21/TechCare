<?php
include 'db.php';
require_once '../phpqrcode/qrlib.php'; // Include the QR code library

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $suffix = $_POST['suffix'];
    $sex = $_POST['sex'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $placeOfBirth = $_POST['placeOfBirth'];
    $religion = $_POST['religion'];
    $citizenship = $_POST['citizenship'];
    $street = $_POST['street'];
    $zone = $_POST['zone'];
    $barangay = $_POST['barangay'];
    $municipal = $_POST['municipal'];
    $province = $_POST['province'];
    $zipcode = $_POST['zipcode'];
    $contactNumber = $_POST['contactNumber'];
    $education = $_POST['educationalAttainment'];
    $occupation = $_POST['occupation'];
    $civilStatus = $_POST['civilStatus'];
    $laborStatus = $_POST['laborStatus'];
    $voterStatus = $_POST['voterStatus'];
    $pwdStatus = $_POST['pwdStatus'];
    $fourP = $_POST['fourP'];
    $covidVaccineStatus = $_POST['covidVaccineStatus'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $idType = $_POST['id-type'];
    $idNumber = $_POST['idNumber'];
    $momFirstName = $_POST['momFirstName'];
    $momLastName = $_POST['momLastName'];

    // Generate QR Code
    $qrData = "Name: $firstName $middleName $lastName\nDOB: $dateOfBirth\nPlace of Birth: $placeOfBirth";
    $path = '../images/';
    $formattedFirstName = preg_replace('/\s+/', '_', $firstName); // Replace spaces with underscores
    $formattedLastName = preg_replace('/\s+/', '_', $lastName); // Replace spaces with underscores
    $timestamp = time();
    $qrImage = "{$formattedFirstName}_{$formattedLastName}_{$timestamp}.png";
    $qrcodePath = $path . $qrImage;
    QRcode::png($qrData, $qrcodePath, 'H', 4, 4);

    // Handle profile picture upload
    $profilePic = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $profilePic = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    }

    // Prepare SQL query
    $sql = "INSERT INTO residents 
            (first_name, middle_name, last_name, suffix_name, gender, date_of_birth, place_of_birth, religion, citizenship, street, zone, barangay, municipal, province, zipcode, contact_number, education, occupation, civil_status, labor_status, voter_status, pwd_status, four_p, status, longitude, latitude, profile_pic, id_type, id_number, mom_name, mom_lname, qr_code)
            VALUES 
            ('$firstName', '$middleName', '$lastName', '$suffix', '$sex', '$dateOfBirth', '$placeOfBirth', '$religion', '$citizenship', '$street', '$zone', '$barangay', '$municipal', '$province', '$zipcode', '$contactNumber', '$education', '$occupation', '$civilStatus', '$laborStatus', '$voterStatus', '$pwdStatus', '$fourP', '$covidVaccineStatus', '$longitude', '$latitude', '$profilePic', '$idType', '$idNumber', '$momFirstName', '$momLastName', '$qrImage')";

    // Execute SQL query and handle response
    if (mysqli_query($conn, $sql)) {
        $response = [
            "success" => true,
            "message" => "New resident added successfully!"
        ];
    } else {
        $response = [
            "success" => false,
            "message" => "Error: " . mysqli_error($conn)
        ];
    }

    // Close database connection
    $conn->close();

    // Return response in JSON format
    echo json_encode($response);
}
?>
