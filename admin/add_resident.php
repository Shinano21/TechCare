<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $first_name = $_POST['firstName'];
    $middle_name = $_POST['middleName'];
    $last_name = $_POST['lastName'];
    $suffix_name = $_POST['suffix'];
    $gender = $_POST['sex'];
    $date_of_birth = $_POST['dob'];
    $place_of_birth = $_POST['pob'];
    $religion = $_POST['religion'];
    $citizenship = $_POST['citizenship'];
    $street = $_POST['streetName'];
    $zone = $_POST['zone'];
    $barangay = $_POST['barangay'];
    $municipal = $_POST['cityMunicipality'];
    $province = $_POST['province'];
    $zipcode = $_POST['zipCode'];
    $contact_number = $_POST['contactNo'];
    $education = $_POST['educationalAttainment'];
    $occupation = $_POST['occupation'];
    $civil_status = $_POST['civilStatus'];
    $labor_status = $_POST['laborForceStatus'];
    $voter_status = $_POST['voterStatus'];
    $pwd_status = $_POST['pwdStatus'];
    $four_p = $_POST['fourPsBeneficiary'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $profile_pic = ""; // handle image upload separately
    $id_type = $_POST['id-type'];
    $id_number = $_POST['id-number'];
    $mom_name = $_POST['momName'];
    $mom_lname = $_POST['momLname'];
    $qr_code = ""; // generate QR code separately

    $sql = "INSERT INTO residents (first_name, middle_name, last_name, suffix_name, gender, date_of_birth, place_of_birth, religion, citizenship, street, zone, barangay, municipal, province, zipcode, contact_number, education, occupation, civil_status, labor_status, voter_status, pwd_status, four_p, longitude, latitude, profile_pic, id_type, id_number, mom_name, mom_lname, qr_code)
    VALUES ('$first_name', '$middle_name', '$last_name', '$suffix_name', '$gender', '$date_of_birth', '$place_of_birth', '$religion', '$citizenship', '$street', '$zone', '$barangay', '$municipal', '$province', '$zipcode', '$contact_number', '$education', '$occupation', '$civil_status', '$labor_status', '$voter_status', '$pwd_status', '$four_p', '$longitude', '$latitude', '$profile_pic', '$id_type', '$id_number', '$mom_name', '$mom_lname', '$qr_code')";

    if ($conn->query($sql) === TRUE) {
        echo "New resident added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
