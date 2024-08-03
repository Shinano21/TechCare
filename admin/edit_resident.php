<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resident</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Edit Resident</h1>

    <?php
    include 'db.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "SELECT * FROM residents WHERE resident_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $resident = $result->fetch_assoc();
        } else {
            echo "<p>Resident not found</p>";
            exit();
        }

        $stmt->close();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $resident_id = $_POST['id'];
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
        $profile_pic = ''; // Handle image upload separately
        $id_type = $_POST['id-type'];
        $id_number = $_POST['id-number'];
        $mom_name = $_POST['momName'];
        $mom_lname = $_POST['momLname'];
        $qr_code = ''; // Handle QR code separately if needed

        // File upload handling
        if ($_FILES['image']['name']) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $profile_pic = $target_file;
        }

        $sql = "UPDATE residents SET first_name=?, middle_name=?, last_name=?, suffix_name=?, gender=?, date_of_birth=?, place_of_birth=?, religion=?, citizenship=?, street=?, zone=?, barangay=?, municipal=?, province=?, zipcode=?, contact_number=?, education=?, occupation=?, civil_status=?, labor_status=?, voter_status=?, pwd_status=?, four_p=?, longitude=?, latitude=?, profile_pic=?, id_type=?, id_number=?, mom_name=?, mom_lname=?, qr_code=? WHERE resident_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssssssssssssssssssssssssii', 
            $first_name, $middle_name, $last_name, $suffix_name, $gender, $date_of_birth, 
            $place_of_birth, $religion, $citizenship, $street, $zone, $barangay, $municipal, 
            $province, $zipcode, $contact_number, $education, $occupation, $civil_status, 
            $labor_status, $voter_status, $pwd_status, $four_p, $longitude, 
            $latitude, $profile_pic, $id_type, $id_number, $mom_name, $mom_lname, $qr_code, 
            $resident_id
        );

        if ($stmt->execute()) {
            header('Location: residents.php'); // Redirect back to the residents page
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $stmt->close();
    }

    $conn->close();
    ?>

    <form action="edit_resident.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $resident['resident_id']; ?>">
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo $resident['first_name']; ?>" />
        </div>
        <div class="form-group">
            <label for="middleName">Middle Name</label>
            <input type="text" id="middleName" name="middleName" class="form-control" value="<?php echo $resident['middle_name']; ?>" />
        </div>
        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo $resident['last_name']; ?>" />
        </div>
        <div class="form-group">
            <label for="suffix">Suffix</label>
            <input type="text" id="suffix" name="suffix" class="form-control" value="<?php echo $resident['suffix_name']; ?>" />
        </div>
        <div class="form-group">
            <label for="sex">Sex</label>
            <input type="text" id="sex" name="sex" class="form-control" value="<?php echo $resident['gender']; ?>" />
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" class="form-control" value="<?php echo $resident['date_of_birth']; ?>" />
        </div>
        <div class="form-group">
            <label for="momName">Mother's First Name</label>
            <input type="text" id="momName" name="momName" class="form-control" value="<?php echo $resident['mom_name']; ?>" />
        </div>
        <div class="form-group">
            <label for="momLname">Mother's Last Name</label>
            <input type="text" id="momLname" name="momLname" class="form-control" value="<?php echo $resident['mom_lname']; ?>" />
        </div>
        <div class="form-group">
            <label for="age">Age</label>
            <input type="text" id="age" name="age" class="form-control" value="<?php echo $resident['age']; ?>" />
        </div>
        <div class="form-group">
            <label for="pob">Place of Birth</label>
            <input type="text" id="pob" name="pob" class="form-control" value="<?php echo $resident['place_of_birth']; ?>" />
        </div>
        <div class="form-group">
            <label for="religion">Religion</label>
            <input type="text" id="religion" name="religion" class="form-control" value="<?php echo $resident['religion']; ?>" />
        </div>
        <div class="form-group">
            <label for="citizenship">Citizenship</label>
            <input type="text" id="citizenship" name="citizenship" class="form-control" value="<?php echo $resident['citizenship']; ?>" />
        </div>
        <div class="form-group">
            <label for="streetName">Street Name</label>
            <input type="text" id="streetName" name="streetName" class="form-control" value="<?php echo $resident['street']; ?>" />
        </div>
        <div class="form-group">
            <label for="zone">Zone</label>
            <input type="text" id="zone" name="zone" class="form-control" value="<?php echo $resident['zone']; ?>" />
        </div>
        <div class="form-group">
            <label for="barangay">Barangay</label>
            <input type="text" id="barangay" name="barangay" class="form-control" value="<?php echo $resident['barangay']; ?>" />
        </div>
        <div class="form-group">
            <label for="cityMunicipality">City/Municipality</label>
            <input type="text" id="cityMunicipality" name="cityMunicipality" class="form-control" value="<?php echo $resident['municipal']; ?>" />
        </div>
        <div class="form-group">
            <label for="province">Province</label>
            <input type="text" id="province" name="province" class="form-control" value="<?php echo $resident['province']; ?>" />
        </div>
        <div class="form-group">
            <label for="zipCode">Zip Code</label>
            <input type="text" id="zipCode" name="zipCode" class="form-control" value="<?php echo $resident['zipcode']; ?>" />
        </div>
        <div class="form-group">
            <label for="contactNo">Contact No.</label>
            <input type="text" id="contactNo" name="contactNo" class="form-control" value="<?php echo $resident['contact_number']; ?>" />
        </div>
        <div class="form-group">
            <label for="educationalAttainment">Educational Attainment</label>
            <input type="text" id="educationalAttainment" name="educationalAttainment" class="form-control" value="<?php echo $resident['education']; ?>" />
        </div>
        <div class="form-group">
            <label for="occupation">Occupation</label>
            <input type="text" id="occupation" name="occupation" class="form-control" value="<?php echo $resident['occupation']; ?>" />
        </div>
        <div class="form-group">
            <label for="civilStatus">Civil Status</label>
            <input type="text" id="civilStatus" name="civilStatus" class="form-control" value="<?php echo $resident['civil_status']; ?>" />
        </div>
        <div class="form-group">
            <label for="laborForceStatus">Labor Force Status</label>
            <input type="text" id="laborForceStatus" name="laborForceStatus" class="form-control" value="<?php echo $resident['labor_status']; ?>" />
        </div>
        <div class="form-group">
            <label for="voterStatus">Voter Status</label>
            <input type="text" id="voterStatus" name="voterStatus" class="form-control" value="<?php echo $resident['voter_status']; ?>" />
        </div>
        <div class="form-group">
            <label for="pwdStatus">PWD Status</label>
            <input type="text" id="pwdStatus" name="pwdStatus" class="form-control" value="<?php echo $resident['pwd_status']; ?>" />
        </div>
        <div class="form-group">
            <label for="fourPsBeneficiary">4Ps Beneficiary</label>
            <input type="text" id="fourPsBeneficiary" name="fourPsBeneficiary" class="form-control" value="<?php echo $resident['four_p']; ?>" />
        </div>
        <div class="form-group">
            <label for="longitude">Longitude</label>
            <input type="text" id="longitude" name="longitude" class="form-control" value="<?php echo $resident['longitude']; ?>" />
        </div>
        <div class="form-group">
            <label for="latitude">Latitude</label>
            <input type="text" id="latitude" name="latitude" class="form-control" value="<?php echo $resident['latitude']; ?>" />
        </div>
        <div class="form-group">
            <label for="covidVaccineStatus">Covid Vaccine Status</label>
            <input type="text" id="covidVaccineStatus" name="covidVaccineStatus" class="form-control" value="<?php echo $resident['covid_vaccine_status']; ?>" />
        </div>
        <div class="form-group">
            <label for="id-type">ID Type</label>
            <select id="id-type" name="id-type" class="form-control">
                <option value="passport" <?php if ($resident['id_type'] == 'passport') echo 'selected'; ?>>Passport</option>
                <option value="driver-license" <?php if ($resident['id_type'] == 'driver-license') echo 'selected'; ?>>Driver's License</option>
                <option value="national-id" <?php if ($resident['id_type'] == 'national-id') echo 'selected'; ?>>National ID</option>
                <option value="philhealth" <?php if ($resident['id_type'] == 'philhealth') echo 'selected'; ?>>PhilHealth</option>
            </select>
        </div>
        <div class="form-group">
            <label for="id-number">ID Number</label>
            <input type="text" id="id-number" name="id-number" class="form-control" value="<?php echo $resident['id_number']; ?>" />
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/*" class="form-control-file" />
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update Resident</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
