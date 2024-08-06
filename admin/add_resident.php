<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $qrCode = $_POST['qrCode'];

    $profilePic = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $profilePic = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    }

    $sql = "INSERT INTO residents (first_name, middle_name, last_name, suffix_name, gender, date_of_birth, place_of_birth, religion, citizenship, street, zone, barangay, municipal, province, zipcode, contact_number, education, occupation, civil_status, labor_status, voter_status, pwd_status, four_p, status, longitude, latitude, profile_pic, id_type, id_number, mom_name, mom_lname, qr_code)
            VALUES ('$firstName', '$middleName', '$lastName', '$suffix', '$sex', '$dateOfBirth', '$placeOfBirth', '$religion', '$citizenship', '$street', '$zone', '$barangay', '$municipal', '$province', '$zipcode', '$contactNumber', '$education', '$occupation', '$civilStatus', '$laborStatus', '$voterStatus', '$pwdStatus', '$fourP', '$covidVaccineStatus', '$longitude', '$latitude', '$profilePic', '$idType', '$idNumber', '$momFirstName', '$momLastName', '$qrCode')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('New resident added successfully');
                window.location.href = 'resident.php';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
