<?php
include 'db.php';

// Fetch all residents for the dropdown
$residents = [];
$resident_sql = "SELECT resident_id, CONCAT(first_name, ' ', middle_name, ' ', last_name, ' (ID: ', resident_id, ')') AS full_name FROM residents";
$resident_result = $conn->query($resident_sql);
if ($resident_result->num_rows > 0) {
    while ($row = $resident_result->fetch_assoc()) {
        $residents[] = $row;
    }
}

// Handle form submission for adding a new record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resident_id = $_POST['resident_id'];
    $nutrition_type = $_POST['nutrition_type'];
    $supplements = $_POST['supplements'];
    $date_assessed = $_POST['date_assessed'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    // Insert new record
    $sql = "INSERT INTO nutrition (resident_id, nutrition_type, supplements, date_assessed, height, weight, status, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssddss", $resident_id, $nutrition_type, $supplements, $date_assessed, $height, $weight, $status, $remarks);

    if ($stmt->execute()) {
        echo "Record added successfully!";
        header("Location: nutrition.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Nutrition Record</title>
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(135deg, #C2FFD8 10%, #465EFB 100%);
            overflow-x:hidden;

        }
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
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
    width:60%;
}

        .sidebar {
            background-color: #f8f9fa;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            padding-top: 20px;
        }
        .sidebar a, .offcanvas-body a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #333;
            display: block;
        }
        .sidebar a i, .offcanvas-body a i {
            margin-right: 10px;
            font-size: 20px;
        }
        .sidebar a:hover, .offcanvas-body a:hover {
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

            <!-- Main Content -->
            <div class="col-lg-10 offset-lg-1 mt-4 main-content">
                <form method="POST" action="">
                    <h1 class="mb-2">Add Nutrition Record</h1>
                    <hr>
                    <div class="mb-3">
                        <label for="resident_id" class="form-label">Resident (Type Name or ID):</label>
                        <select id="resident_id" name="resident_id" class="form-select select2" required>
                            <option value="">Select Resident</option>
                            <?php foreach ($residents as $resident): ?>
                                <option value="<?php echo $resident['resident_id']; ?>">
                                    <?php echo $resident['full_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nutrition_type" class="form-label">Nutrition Type:</label>
                        <input type="text" id="nutrition_type" name="nutrition_type" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="supplements" class="form-label">Supplements:</label>
                        <textarea id="supplements" name="supplements" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="date_assessed" class="form-label">Date Assessed:</label>
                        <input type="date" id="date_assessed" name="date_assessed" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="height" class="form-label">Height (cm):</label>
                        <input type="number" step="0.01" id="height" name="height" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="weight" class="form-label">Weight (kg):</label>
                        <input type="number" step="0.01" id="weight" name="weight" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <input type="text" id="status" name="status" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks:</label>
                        <textarea id="remarks" name="remarks" class="form-control"></textarea>
                    </div>

                    <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-primary">Add Record</button>
                </div>
                <div class="text-center mt-3">
                <a href="nutrition.php" class="btn btn-link ms-2">Back to Nutrition Records</a>
                    </div>
                </form>
                <br>
            </div>
        </div>
    </div>

    <!-- Include Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>

