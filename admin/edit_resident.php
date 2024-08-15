<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resident</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
</head>
<style>
          body {
        font-family: 'Poppins', sans-serif;
        background-image: linear-gradient(135deg, #c2ffd8 10%, #465efb 100%);
        overflow-x: hidden;
      }
      .main-content {
        margin-left: 250px;
        padding: 30px;
      }
     .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .sidebar a, .offcanvas-body a  {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #333;
            display: block;
        }
        .sidebar a i,  .offcanvas-body a i {
            margin-right: 10px;
            font-size: 20px;
        }
        .sidebar a:hover,  .offcanvas-body a:hover {
            background-color: #7AB2B2;
        }
        .select-image {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 150px;
        background-color: #E8F0F2;
        margin-bottom: 15px;
        border: 1px solid #ced4da;
        border-radius: 5px;
    }
    .select-image img {
        max-height: 100%;
        max-width: 100%;
    }
    @media (max-width: 992px) {
  .main-content {
    margin-left: 0;
    padding: 20px;
  }
  
}
</style>
<body>
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
        $target_dir = "../profile/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        // Check if the file upload was successful
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $params[array_search('profile_pic', $columns)] = $target_file;
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        // Retain the old profile picture if no new file is uploaded
        $params[array_search('profile_pic', $columns)] = $resident['profile_pic'];
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


<div class="container-fluid">
      <div class="row ">
          <!-- Sidebar for Larger Screens -->
          <div class="col-lg d-none d-lg-block sidebar">
              <div class="text-center mb-5">
                  <img src="../images/LOGO.svg" alt="TechCare" width="100">
              </div>
              <a href="Dashboard.php"><i class="bi bi-speedometer2"></i>Dashboard</a>
              <a href="resident.php"><i class="bi bi-people"></i>Residents</a>

              <!-- Services Dropdown -->
              <div class="dropdown">
                  <a href="#" class="dropdown-toggle" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-wrench"></i>Services
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                      <li><a class="dropdown-item" href="admin_create.php">Create Account</a></li>
                      <li><a class="dropdown-item" href="#">Nutrition</a></li>
                      <li><a class="dropdown-item" href="#">More</a></li>
                  </ul>
              </div>

              <a href="#"><i class="bi bi-bar-chart-line"></i>Visualization</a>
              <a href="#"><i class="bi bi-file-earmark-text"></i>Reports</a>
              <a href="#"><i class="bi bi-globe"></i>Website</a>
              <a href="#"><i class="bi bi-person-badge"></i>ID System</a>
              <a href="#" onclick="showLogoutModal()"><i class="bi bi-box-arrow-right"></i>Logout</a>
          </div>

          <!-- Offcanvas Sidebar for Smaller Screens -->
          <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
              <div class="offcanvas-header">
                  <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                  <div class="text-center mb-5">
                      <img src="../images/LOGO.svg" alt="TechCare" width="100">
                  </div>
                  <a href="#"><i class="bi bi-speedometer2"></i>Dashboard</a>
                  <a href="Resident.php"><i class="bi bi-people"></i>Residents</a>

                  <!-- Services Dropdown -->
                  <div class="dropdown">
                      <a href="#" class="dropdown-toggle" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="bi bi-wrench"></i>Services
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                          <li><a class="dropdown-item" href="admin_create.php">Create Account</a></li>
                          <li><a class="dropdown-item" href="immunization.php">Immunization</a></li>
                          <li><a class="dropdown-item" href="#">More</a></li>
                      </ul>
                  </div>

                  <a href="#"><i class="bi bi-bar-chart-line"></i>Visualization</a>
                  <a href="#"><i class="bi bi-file-earmark-text"></i>Reports</a>
                  <a href="#"><i class="bi bi-globe"></i>Website</a>
                  <a href="#"><i class="bi bi-person-badge"></i>ID System</a>
                  <a href="#" onclick="showLogoutModal()"><i class="bi bi-box-arrow-right"></i>Logout</a>
              </div>
          </div>

          <!-- Sidebar Toggle Button for Smaller Screens -->
          <button class="btn btn-primary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
              <i class="bi bi-list"></i>
          </button>

          <div class="col-lg offset-lg-3 mt-4 mt-lg-0 main-content" >

    <form action="edit_resident.php" method="POST" enctype="multipart/form-data"  style="background-color: white; padding: 20px;">
        <input type="hidden" name="id" value="<?php echo $resident['resident_id']; ?>">
        <h1>Update Resident</h1>
        <hr>
        <div class="row align-items-end mb-5">
            <div class="col-md-3 form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="first_name" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['first_name']); ?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="middleName">Middle Name</label>
                <input type="text" id="middleName" name="middle_name" class="form-control border border-secondary" value="<?php echo htmlspecialchars($resident['middle_name']); ?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="last_name" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['last_name']); ?>" />
            </div>
            <div class="col-md-3 form-group">
            <div class="select-image">
                <img src="fetch_image.php?id=<?php echo htmlspecialchars($resident['resident_id']); ?>" alt="Resident Image" id="image-preview">
            </div>
            <input
                type="file"
                id="image"
                name="image"
                accept="image/*"
                class="form-control-file"
                onchange="previewImage(event)"
            />
        </div>

        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="suffix">Suffix</label>
                <input type="text" id="suffix" name="suffix_name" class="form-control border border-secondary" value="<?php echo htmlspecialchars($resident['suffix_name']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="sex">Sex</label>
                <select id="sex" name="gender" class="form-control border border-secondary" required>
                    <option value="male" <?php if ($resident['gender'] == 'male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if ($resident['gender'] == 'female') echo 'selected'; ?>>Female</option>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="date_of_birth" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['date_of_birth']); ?>" />
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-4 form-group">
                <label for="pob">Place of Birth</label>
                <input type="text" id="pob" name="place_of_birth" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['place_of_birth']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="religion">Religion</label>
                <input type="text" id="religion" name="religion" class="form-control border border-secondary" value="<?php echo htmlspecialchars($resident['religion']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="citizenship">Citizenship</label>
                <input type="text" id="citizenship" name="citizenship" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['citizenship']); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="streetName">Street Name</label>
                <input type="text" id="streetName" name="street" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['street']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="zone">Zone</label>
                <input type="text" id="zone" name="zone" class="form-control border border-secondary" value="<?php echo htmlspecialchars($resident['zone']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="barangay">Barangay</label>
                <input type="text" id="barangay" name="barangay" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['barangay']); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="cityMunicipality">City/Municipality</label>
                <input type="text" id="cityMunicipality" name="municipal" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['municipal']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="province">Province</label>
                <input type="text" id="province" name="province" class="form-control border border-secondary" value="<?php echo htmlspecialchars($resident['province']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="zipCode">Zip Code</label>
                <input type="text" id="zipCode" name="zipcode" class="form-control border border-secondary" value="<?php echo htmlspecialchars($resident['zipcode']); ?>" />
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-4 form-group">
                <label for="contactNo">Contact No.</label>
                <input type="text" id="contactNo" name="contact_number" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['contact_number']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="educationalAttainment">Educational Attainment</label>
                <input type="text" id="educationalAttainment" name="education" class="form-control border border-secondary" value="<?php echo htmlspecialchars($resident['education']); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="occupation">Occupation</label>
                <input type="text" id="occupation" name="occupation" class="form-control border border-secondary" value="<?php echo htmlspecialchars($resident['occupation']); ?>" />
            </div>
        </div>
        <div class="row">
         <div class="row">
    <div class="col-md-4 form-group">
        <label for="civilStatus">Civil Status</label>
        <select id="civilStatus" name="civil_status" class="form-control border border-secondary" required>
            <option value="" selected disabled hidden></option>
            <option value="Single" <?php if ($resident['civil_status'] == 'Single') echo 'selected'; ?>>Single</option>
            <option value="Married" <?php if ($resident['civil_status'] == 'Married') echo 'selected'; ?>>Married</option>
            <option value="Widowed" <?php if ($resident['civil_status'] == 'Widowed') echo 'selected'; ?>>Widowed</option>
            <option value="Divorced" <?php if ($resident['civil_status'] == 'Divorced') echo 'selected'; ?>>Divorced</option>
        </select>
    </div>
    <div class="col-md-4 form-group">
        <label for="laborStatus">Labor Status</label>
        <select id="laborStatus" name="labor_status" class="form-control border border-secondary">
            <option value="" selected disabled hidden></option>
            <option value="Employed" <?php if ($resident['labor_status'] == 'Employed') echo 'selected'; ?>>Employed</option>
            <option value="Unemployed" <?php if ($resident['labor_status'] == 'Unemployed') echo 'selected'; ?>>Unemployed</option>
            <option value="Self-Employed" <?php if ($resident['labor_status'] == 'Self-Employed') echo 'selected'; ?>>Self-Employed</option>
            <option value="Student" <?php if ($resident['labor_status'] == 'Student') echo 'selected'; ?>>Student</option>
            <option value="Retired" <?php if ($resident['labor_status'] == 'Retired') echo 'selected'; ?>>Retired</option>
            <option value="Homemaker" <?php if ($resident['labor_status'] == 'Homemaker') echo 'selected'; ?>>Homemaker</option>
            <option value="Unable to work" <?php if ($resident['labor_status'] == 'Unable to work') echo 'selected'; ?>>Unable to work</option>
        </select>
    </div>
    <div class="col-md-4 form-group">
        <label for="voterStatus">Voter Status</label>
        <select id="voterStatus" name="voter_status" class="form-control border border-secondary">
            <option value="" selected disabled hidden></option>
            <option value="Registered" <?php if ($resident['voter_status'] == 'Registered') echo 'selected'; ?>>Registered</option>
            <option value="Unregistered" <?php if ($resident['voter_status'] == 'Unregistered') echo 'selected'; ?>>Unregistered</option>
        </select>
    </div>
</div>
<div class="row mb-5 justify-content-md-center">
    <div class="col-md-4 form-group">
        <label for="pwdStatus">PWD Status</label>
        <select id="pwdStatus" name="pwd_status" class="form-control border border-secondary">
            <option value="" selected disabled hidden></option>
            <option value="yes" <?php if ($resident['pwd_status'] == 'yes') echo 'selected'; ?>>Yes</option>
            <option value="no" <?php if ($resident['pwd_status'] == 'no') echo 'selected'; ?>>No</option>
            <option value="pending" <?php if ($resident['pwd_status'] == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="unknown" <?php if ($resident['pwd_status'] == 'unknown') echo 'selected'; ?>>Unknown</option>
        </select>
    </div>
    <div class="col-md-4 form-group">
        <label for="fourPsBeneficiary">4P's Beneficiary</label>
        <select id="fourPsBeneficiary" name="four_p" class="form-control border border-secondary">
            <option value="" selected disabled hidden></option>
            <option value="Yes" <?php if ($resident['four_p'] == 'Yes') echo 'selected'; ?>>Yes</option>
            <option value="No" <?php if ($resident['four_p'] == 'No') echo 'selected'; ?>>No</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-4 form-group">
        <label for="longitude">Longitude</label>
        <input type="text" id="longitude" name="longitude" class="form-control border border-secondary" value="<?php echo htmlspecialchars($resident['longitude']); ?>" />
    </div>
    <div class="col-md-4 form-group">
        <label for="latitude">Latitude</label>
        <input type="text" id="latitude" name="latitude" class="form-control border border-secondary" value="<?php echo htmlspecialchars($resident['latitude']); ?>" />
    </div>
    <div class="col-md-4 form-group">
        <label for="covidVaccineStatus">Covid Vaccine Status</label>
        <select id="covidVaccineStatus" name="status" class="form-control border border-secondary">
            <option value="" selected disabled hidden></option>
            <option value="vaccinated" <?php if ($resident['status'] == 'vaccinated') echo 'selected'; ?>>Vaccinated</option>
            <option value="unvaccinated" <?php if ($resident['status'] == 'unvaccinated') echo 'selected'; ?>>Unvaccinated</option>
        </select>
    </div>
</div>
<div class="row mb-5">
    <div class="col-md-3 form-group">
        <label for="id-type">ID Type</label>
        <select id="id-type" name="id_type" class="form-control border border-secondary" required>
            <option value="" selected disabled hidden></option>
            <option value="passport" <?php if ($resident['id_type'] == 'passport') echo 'selected'; ?>>Passport</option>
            <option value="driver-license" <?php if ($resident['id_type'] == 'driver-license') echo 'selected'; ?>>Driver's License</option>
            <option value="national-id" <?php if ($resident['id_type'] == 'national-id') echo 'selected'; ?>>National ID</option>
            <option value="philhealth" <?php if ($resident['id_type'] == 'philhealth') echo 'selected'; ?>>PhilHealth</option>
        </select>
    </div>
    <div class="col-md-3 form-group">
        <label for="idNumber">ID Number</label>
        <input type="text" id="idNumber" name="id_number" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['id_number']); ?>" />
    </div>
    <div class="col-md-3 form-group">
        <label for="momFirstName">Mother's First Name</label>
        <input type="text" id="momFirstName" name="mom_name" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['mom_name']); ?>" />
    </div>
    <div class="col-md-3 form-group">
        <label for="momLastName">Mother's Last Name</label>
        <input type="text" id="momLastName" name="mom_lname" class="form-control border border-secondary" required value="<?php echo htmlspecialchars($resident['mom_lname']); ?>" />
    </div>
</div>

<div class="col-md-3 form-group">
    <label for="qrCode">QR Code</label>
    <input type="text" id="qrCode" name="qr_code" class="form-control" value="<?php echo htmlspecialchars($resident['qr_code']); ?>" />
    <?php if (!empty($resident['qr_code'])): ?>
        <img src="../qr/<?php echo htmlspecialchars($resident['qr_code']); ?>" alt="QR Code" style="max-width: 100%; margin-top: 10px;">
    <?php endif; ?>
</div>
        </div>
        <div class="row">
            <div class="col text-end">
                <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
            </div>
          </div>
    </form>
    </div>
</div>

    <!--This is the preview image script-->
    <script>
    function previewImage(event) {
        const imagePreview = document.getElementById('image-preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            }

            reader.readAsDataURL(file);
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
