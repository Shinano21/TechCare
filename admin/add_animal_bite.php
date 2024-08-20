<?php
include 'db.php'; // Include the database connection

// Fetch all residents for the dropdown
$residents = [];
$resident_sql = "SELECT resident_id, CONCAT(first_name, ' ', middle_name, ' ', last_name, ' (ID: ', id_number, ')') AS full_name FROM residents";
$resident_result = $conn->query($resident_sql);
if ($resident_result->num_rows > 0) {
    while ($row = $resident_result->fetch_assoc()) {
        $residents[] = $row;
    }
}

// Handle form submission and insert record into the database
$success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resident_id = $_POST['resident_id'];
    $date_of_exposure = $_POST['date_of_exposure'];
    $nature_of_bite = $_POST['nature_of_bite'];
    $category_of_bite = $_POST['category_of_bite'];
    $actions_taken = $_POST['actions_taken'];

    // Insert the record into the animal_bite table
    $sql = "INSERT INTO animal_bite (resident_id, date_of_exposure, nature_of_bite, category_of_bite, actions_taken) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $resident_id, $date_of_exposure, $nature_of_bite, $category_of_bite, $actions_taken);

    if ($stmt->execute()) {
        $success = true;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Animal Bite Record</title>
    <link href="https://cdn.jsdelivr.net/npm/choices.js@9.1.0/public/assets/styles/choices.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js@9.1.0/public/assets/scripts/choices.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(135deg, #C2FFD8 10%, #465EFB 100%);
            overflow-x: hidden;
        }
        .main-content {
            min-height: 100vh;
            margin-left: 250px;
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .main-content form {
            display: block;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 60%;
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
            </div>

            <!-- Sidebar Toggle Button for Smaller Screens -->
            <button class="btn btn-primary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                <i class="bi bi-list"></i>
            </button>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 offset-lg-1 mt-4 main-content">
                <form method="POST" action="">
                    <h1 class="mb-2">Add Animal Bite Record</h1>
                    <hr>
                    <div class="mb-3">
                        <label for="resident_id" class="form-label">Select Resident:</label>
                        <select id="resident_id" name="resident_id" class="form-select" required>
                            <option value="">Select Resident</option>
                            <?php foreach ($residents as $resident): ?>
                                <option value="<?php echo $resident['resident_id']; ?>">
                                    <?php echo $resident['full_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date_of_exposure" class="form-label">Date of Exposure:</label>
                        <input type="date" id="date_of_exposure" name="date_of_exposure" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="nature_of_bite" class="form-label">Nature of Bite:</label>
                        <input type="text" id="nature_of_bite" name="nature_of_bite" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_of_bite" class="form-label">Category of Bite:</label>
                        <select id="category_of_bite" name="category_of_bite" class="form-control" required>
                            <option value="" disabled selected>Select a category</option>
                            <option value="Category I">Category I</option>
                            <option value="Category II">Category II</option>
                            <option value="Category III">Category III</option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="actions_taken" class="form-label">Actions Taken:</label>
                        <input type="text" id="actions_taken" name="actions_taken" class="form-control" required>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary">Add Record</button>
                    </div>
                    <div class="text-center mt-3">
                        <a href="animal_bite.php" class="btn btn-link ms-2">Back to Animal Bite Records</a>
                    </div>
                </form>
                <br>
            </div>
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
                    Record added successfully!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="redirectButton">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!--Activates the modal-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($success): ?>
                var successModal = new bootstrap.Modal(document.getElementById('successModal'), {
                    keyboard: false
                });
                successModal.show();

                document.getElementById('redirectButton').addEventListener('click', function() {
                    window.location.href = 'animal_bite.php';
                });
            <?php endif; ?>
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var residentSelect = document.getElementById('resident_id');
        new Choices(residentSelect, {
            searchEnabled: true,
            itemSelectText: '',
            placeholderValue: 'Select Resident',
        });
    });
</script>


    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
