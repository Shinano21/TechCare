<?php
include 'db.php'; // Include the database connection

// Fetch all records from the animal_bite table
$sql = "SELECT * FROM animal_bite";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Bite Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient( 135deg, #C2FFD8 10%, #465EFB 100%);
        }
        .container-fluid {
    margin-left: 250px; /* Retain this for the sidebar */
    min-height: 100vh;
    width: calc(100% - 250px); /* Adjust width to account for the sidebar */
    overflow-x: auto; /* Prevents overflow issues */
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
<div class="container p-4">
    <h1 class="mb-5">Animal Bite Records Dashboard</h1>

    <div class="mb-3 text-end">
    <a href="add_animal_bite.php" class="btn btn-primary">Add New Record</a>
</div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Resident ID</th>
                    <th>Date of Exposure</th>
                    <th>Nature of Bite</th>
                    <th>Category of Bite</th>
                    <th>Actions Taken</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['anibite_id'] . "</td>
                            <td>" . $row['resident_id'] . "</td>
                            <td>" . $row['date_of_exposure'] . "</td>
                            <td>" . $row['nature_of_bite'] . "</td>
                            <td>" . $row['category_of_bite'] . "</td>
                            <td>" . $row['actions_taken'] . "</td>
                            <td>
                                <a href='edit_animal_bite.php?anibite_id=" . $row['anibite_id'] . "' class='btn btn-sm btn-warning'><i class='bi bi-pencil'></i> Edit</a>
                                <a href='#' class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' data-href='delete_animal_bite.php?anibite_id=" . $row['anibite_id'] . "'><i class='bi bi-trash'></i> Delete</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No records found</td></tr>";
            }
            ?>
        </tbody>

        </table>
    </div>
</div>
</div>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this record?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget; // Button that triggered the modal
      var href = button.getAttribute('data-href'); // Extract info from data-* attributes
      var confirmButton = deleteModal.querySelector('#confirmDeleteBtn');

      confirmButton.onclick = function() {
        fetch(href, {
          method: 'GET'
        }).then(response => {
          if (response.ok) {
            window.location.reload(); // Refresh the page to reflect the changes
          } else {
            alert('Failed to delete the record.');
          }
        }).catch(error => {
          alert('Error occurred: ' + error);
        });
      };
    });
  });
</script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
