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
        $params = [];
        $types = '';
        $columns = [
            'first_name', 'middle_name', 'last_name', 'suffix_name', 'gender', 'date_of_birth', 
            'place_of_birth', 'religion', 'citizenship', 'street', 'zone', 'barangay', 'municipal', 
            'province', 'zipcode', 'contact_number', 'education', 'occupation', 'civil_status', 
            'labor_status', 'voter_status', 'pwd_status', 'four_p', 'status', 'longitude', 'latitude', 
            'profile_pic', 'id_type', 'id_number', 'mom_name', 'mom_lname', 'qr_code'
        ];

        foreach ($columns as $column) {
            $params[] = $_POST[$column] ?? '';
            $types .= 's';
        }

        // File upload handling
        if ($_FILES['image']['name']) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $params[array_search('profile_pic', $columns)] = $target_file;
        }

        $params[] = $_POST['id'];
        $types .= 'i';

        $sql = "UPDATE residents SET " . implode('=?, ', $columns) . "=? WHERE resident_id=?";
        $stmt = $conn->prepare($sql);

        $bind_names[] = $types;
        for ($i = 0; $i < count($params); $i++) {
            $bind_name = 'bind' . $i;
            $$bind_name = $params[$i];
            $bind_names[] = &$$bind_name;
        }
        call_user_func_array([$stmt, 'bind_param'], $bind_names);

        if ($stmt->execute()) {
            header('Location: resident.php'); // Redirect back to the residents page
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
        
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="first_name" class="form-control" required value="<?php echo htmlspecialchars($resident['first_name']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="middleName">Middle Name</label>
                <input type="text" id="middleName" name="middle_name" class="form-control" value="<?php echo htmlspecialchars($resident['middle_name']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="last_name" class="form-control" required value="<?php echo htmlspecialchars($resident['last_name']); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="suffix">Suffix</label>
                <input type="text" id="suffix" name="suffix_name" class="form-control" value="<?php echo htmlspecialchars($resident['suffix_name']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="sex">Sex</label>
                <select id="sex" name="gender" class="form-control" required>
                    <option value="male" <?php if ($resident['gender'] == 'male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if ($resident['gender'] == 'female') echo 'selected'; ?>>Female</option>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="date_of_birth" class="form-control" required value="<?php echo htmlspecialchars($resident['date_of_birth']); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="pob">Place of Birth</label>
                <input type="text" id="pob" name="place_of_birth" class="form-control" required value="<?php echo htmlspecialchars($resident['place_of_birth']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="religion">Religion</label>
                <input type="text" id="religion" name="religion" class="form-control" value="<?php echo htmlspecialchars($resident['religion']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="citizenship">Citizenship</label>
                <input type="text" id="citizenship" name="citizenship" class="form-control" required value="<?php echo htmlspecialchars($resident['citizenship']); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="streetName">Street Name</label>
                <input type="text" id="streetName" name="street" class="form-control" required value="<?php echo htmlspecialchars($resident['street']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="zone">Zone</label>
                <input type="text" id="zone" name="zone" class="form-control" value="<?php echo htmlspecialchars($resident['zone']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="barangay">Barangay</label>
                <input type="text" id="barangay" name="barangay" class="form-control" required value="<?php echo htmlspecialchars($resident['barangay']); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="cityMunicipality">City/Municipality</label>
                <input type="text" id="cityMunicipality" name="municipal" class="form-control" required value="<?php echo htmlspecialchars($resident['municipal']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="province">Province</label>
                <input type="text" id="province" name="province" class="form-control" value="<?php echo htmlspecialchars($resident['province']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="zipCode">Zip Code</label>
                <input type="text" id="zipCode" name="zipcode" class="form-control" value="<?php echo htmlspecialchars($resident['zipcode']); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="contactNo">Contact No.</label>
                <input type="text" id="contactNo" name="contact_number" class="form-control" required value="<?php echo htmlspecialchars($resident['contact_number']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="educationalAttainment">Educational Attainment</label>
                <input type="text" id="educationalAttainment" name="education" class="form-control" value="<?php echo htmlspecialchars($resident['education']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="occupation">Occupation</label>
                <input type="text" id="occupation" name="occupation" class="form-control" value="<?php echo htmlspecialchars($resident['occupation']); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="civilStatus">Civil Status</label>
                <input type="text" id="civilStatus" name="civil_status" class="form-control" value="<?php echo htmlspecialchars($resident['civil_status']); ?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="laborStatus">Labor Status</label>
                <input type="text" id="laborStatus" name="labor_status" class="form-control" value="<?php echo htmlspecialchars($resident['labor_status']); ?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="voterStatus">Voter Status</label>
                <input type="text" id="voterStatus" name="voter_status" class="form-control" value="<?php echo htmlspecialchars($resident['voter_status']); ?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="pwdStatus">PWD Status</label>
                <input type="text" id="pwdStatus" name="pwd_status" class="form-control" value="<?php echo htmlspecialchars($resident['pwd_status']); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="fourP">4P's</label>
                <input type="text" id="fourP" name="four_p" class="form-control" value="<?php echo htmlspecialchars($resident['four_p']); ?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="status">Status</label>
                <input type="text" id="status" name="status" class="form-control" value="<?php echo htmlspecialchars($resident['status']); ?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="longitude">Longitude</label>
                <input type="text" id="longitude" name="longitude" class="form-control" value="<?php echo htmlspecialchars($resident['longitude']); ?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="latitude">Latitude</label>
                <input type="text" id="latitude" name="latitude" class="form-control" value="<?php echo htmlspecialchars($resident['latitude']); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="profilePic">Profile Picture</label>
                <input type="file" id="profilePic" name="image" class="form-control" />
                <?php if ($resident['profile_pic']) { ?>
                    <img src="<?php echo htmlspecialchars($resident['profile_pic']); ?>" alt="Profile Picture" width="100">
                <?php } ?>
            </div>
            <div class="col-md-4 form-group">
                <label for="idType">ID Type</label>
                <input type="text" id="idType" name="id_type" class="form-control" value="<?php echo htmlspecialchars($resident['id_type']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="idNumber">ID Number</label>
                <input type="text" id="idNumber" name="id_number" class="form-control" value="<?php echo htmlspecialchars($resident['id_number']); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="momFirstName">Mother's First Name</label>
                <input type="text" id="momFirstName" name="mom_name" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['mom_name']); ?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="momLastName">Mother's Last Name</label>
                <input type="text" id="momLastName" name="mom_lname" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['mom_lname']); ?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="qrCode">QR Code</label>
                <input type="text" id="qrCode" name="qr_code" class="form-control" value="<?php echo htmlspecialchars($resident['qr_code']); ?>" />
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
    </form>
</div>
</body>
</html>
