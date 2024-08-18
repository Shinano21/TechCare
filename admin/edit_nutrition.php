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

// Fetch the record to be edited
if (isset($_GET['id'])) {
    $nutrition_id = $_GET['id'];
    $sql = "SELECT * FROM nutrition WHERE nutrition_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $nutrition_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $nutrition = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission for updating the record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nutrition_id = $_POST['nutrition_id'];
    $resident_id = $_POST['resident_id'];
    $nutrition_type = $_POST['nutrition_type'];
    $supplements = $_POST['supplements'];
    $date_assessed = $_POST['date_assessed'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    // Update record
    $sql = "UPDATE nutrition SET resident_id=?, nutrition_type=?, supplements=?, date_assessed=?, height=?, weight=?, status=?, remarks=? WHERE nutrition_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssddssi", $resident_id, $nutrition_type, $supplements, $date_assessed, $height, $weight, $status, $remarks, $nutrition_id);

    if ($stmt->execute()) {
        echo "<script>
                var updateSuccess = true;
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $stmt->error . "');
              </script>";
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
    <title>Edit Nutrition Record</title>
    <!-- Include Select2 CSS -->
     <!-- Include Choices.js CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(135deg, #C2FFD8 10%, #465EFB 100%);
            overflow-x:hidden;

        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: auto;
            margin-top: 50px;
            margin-bottom:50px;
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

    <div class="container">
        <div class="form-container" style="margin-left:500px;">
            <h1>Edit Nutrition Record</h1>
            <hr>
            <form method="POST" action="">
                <input type="hidden" name="nutrition_id" value="<?php echo $nutrition['nutrition_id']; ?>">

                <div class="mb-3">
                    <label for="resident_id" class="form-label">Resident (Type Name or ID):</label>
                    <select id="resident_id" name="resident_id" class="form-select select2" required>
                        <?php foreach ($residents as $resident): ?>
                            <option value="<?php echo $resident['resident_id']; ?>" <?php if ($resident['resident_id'] == $nutrition['resident_id']) echo 'selected'; ?>>
                                <?php echo $resident['full_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nutrition_type" class="form-label">Nutrition Type:</label>
                    <input type="text" id="nutrition_type" name="nutrition_type" class="form-control" value="<?php echo $nutrition['nutrition_type']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="supplements" class="form-label">Supplements:</label>
                    <textarea id="supplements" name="supplements" class="form-control"><?php echo $nutrition['supplements']; ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="date_assessed" class="form-label">Date Assessed:</label>
                    <input type="date" id="date_assessed" name="date_assessed" class="form-control" value="<?php echo $nutrition['date_assessed']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="height" class="form-label">Height (cm):</label>
                    <input type="number" step="0.01" id="height" name="height" class="form-control" value="<?php echo $nutrition['height']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="weight" class="form-label">Weight (kg):</label>
                    <input type="number" step="0.01" id="weight" name="weight" class="form-control" value="<?php echo $nutrition['weight']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <input type="text" id="status" name="status" class="form-control" value="<?php echo $nutrition['status']; ?>">
                </div>

                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks:</label>
                    <textarea id="remarks" name="remarks" class="form-control"><?php echo $nutrition['remarks']; ?></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Update Record</button>
                </div>
            </form>
            <br>
            <div class="text-center">
                <a href="nutrition.php" class="btn btn-link">Back to Nutrition Records</a>
            </div>
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
                Nutrition record updated successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="successRedirectButton">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof updateSuccess !== 'undefined' && updateSuccess) {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();

            // Redirect after the user clicks OK
            document.getElementById('successRedirectButton').addEventListener('click', function () {
                window.location.href = 'nutrition.php';
            });
        }
    });
</script>

     <!-- Include Choices.js JS -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectElement = document.querySelector('.select2');
        const choices = new Choices(selectElement, {
            placeholder: true,
            searchPlaceholderValue: 'Type resident name or ID',
            removeItemButton: true,
        });
    });
</script>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
