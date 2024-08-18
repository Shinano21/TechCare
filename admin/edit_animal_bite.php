<?php
include 'db.php'; // Include the database connection

$row = []; // Initialize $row to avoid undefined variable warnings

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
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                });
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Animal Bite Record</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <style>
         body {
        font-family: 'Poppins', sans-serif;
        background-image: linear-gradient( 135deg, #C2FFD8 10%, #465EFB 100%);
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
        .form-wrapper {
        max-width: 600px; /* Adjust width as needed */
        width: 100%;
        background-color: #fff; /* White background */
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-left:320px;
        margin-top:50px;
        margin-bottom:50px;
        }

    </style>
</head>
<body>
<div class="container-fluid">
        <div class="row">
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
                        <li><a class="dropdown-item" href="immunization.php">Immunization</a></li>
                        <li><a class="dropdown-item" href="nutrition.php">Nutrition</a></li>
                        <li><a class="dropdown-item" href="animal_bite.php">Animal Bite</a></li>
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
                        <li><a class="dropdown-item" href="nutrition.php">Nutrition</a></li>
                        <li><a class="dropdown-item" href="animal_bite.php">Animal Bite</a></li>
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

            <div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="form-wrapper">
  <form method="POST" action="edit_animal_bite.php">
    <h1>Edit Animal Bite Record</h1>
    <hr>
    <input type="hidden" name="anibite_id" value="<?php echo isset($row['anibite_id']) ? htmlspecialchars($row['anibite_id']) : ''; ?>">

    <div class="mb-3">
        <label for="resident_id" class="form-label">Resident ID</label>
        <input type="number" class="form-control" id="resident_id" name="resident_id" value="<?php echo isset($row['resident_id']) ? htmlspecialchars($row['resident_id']) : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="date_of_exposure" class="form-label">Date of Exposure</label>
        <input type="date" class="form-control" id="date_of_exposure" name="date_of_exposure" value="<?php echo isset($row['date_of_exposure']) ? htmlspecialchars($row['date_of_exposure']) : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="nature_of_bite" class="form-label">Nature of Bite</label>
        <input type="text" class="form-control" id="nature_of_bite" name="nature_of_bite" value="<?php echo isset($row['nature_of_bite']) ? htmlspecialchars($row['nature_of_bite']) : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="category_of_bite" class="form-label">Category of Bite</label>
        <input type="text" class="form-control" id="category_of_bite" name="category_of_bite" value="<?php echo isset($row['category_of_bite']) ? htmlspecialchars($row['category_of_bite']) : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="actions_taken" class="form-label">Actions Taken</label>
        <textarea class="form-control" id="actions_taken" name="actions_taken" rows="4" required><?php echo isset($row['actions_taken']) ? htmlspecialchars($row['actions_taken']) : ''; ?></textarea>
    </div>

    <div class="d-grid mt-5">
        <button type="submit" class="btn btn-primary">Update Record</button>
    </div>
    <div class="text-center mt-3">
        <a href="animal_bite.php" class="btn btn-link ms-2">Back to Animal Bite Records</a>
    </div>
</form>

  </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Record updated successfully!
            </div>
            <div class="modal-footer">
                <a href="animal_bite.php" class="btn btn-primary">OK</a>
            </div>
        </div>
    </div>
</div>


<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
