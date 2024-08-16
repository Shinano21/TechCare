<?php
include 'db.php';

// Handle delete operation
if (isset($_GET['delete'])) {
    $nutrition_id = $_GET['delete'];
    $sql = "DELETE FROM nutrition WHERE nutrition_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $nutrition_id);

    if ($stmt->execute()) {
        echo "Record deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all nutrition records
$sql = "SELECT n.nutrition_id, CONCAT(r.first_name, ' ', r.middle_name, ' ', r.last_name) AS resident_name, n.nutrition_type, n.supplements, n.date_assessed, n.height, n.weight, n.status, n.remarks
        FROM nutrition n
        JOIN residents r ON n.resident_id = r.resident_id";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(135deg, #C2FFD8 10%, #465EFB 100%);
        }
        .main-content {
            min-height: 100vh; /* Use min-height to allow for dynamic content */
            margin-left: 250px;
    padding-left: 30px;   /* Left padding */
    padding-right: 30px;   /* Left padding */
        }
        .sidebar {
            background-color: #f8f9fa;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
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
        /* Add this to your existing CSS */
.table-container {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    width: 100%; /* Ensure the table container takes the full width */
}

.table {
    width: 100%; /* Ensure the table itself is 100% wide */
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
                <h1>Nutrition Records</h1>
                <div class="d-flex justify-content-end">
                    <a href="add_nutrition.php" class="btn btn-primary mb-3">Add New Record</a>
                </div>

                
                <!-- Table Container -->
                <div class="table-container mt-3">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Resident</th>
                                <th>Nutrition Type</th>
                                <th>Supplements</th>
                                <th>Date Assessed</th>
                                <th>Height (cm)</th>
                                <th>Weight (kg)</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['resident_name']; ?></td>
                                        <td><?php echo $row['nutrition_type']; ?></td>
                                        <td><?php echo $row['supplements']; ?></td>
                                        <td><?php echo $row['date_assessed']; ?></td>
                                        <td><?php echo $row['height']; ?></td>
                                        <td><?php echo $row['weight']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                        <td><?php echo $row['remarks']; ?></td>
                                        <td>
                                            <a href="edit_nutrition.php?id=<?php echo $row['nutrition_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="?delete=<?php echo $row['nutrition_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">No records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

