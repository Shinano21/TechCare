<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

// Prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TechCare Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    
    <style>
      body {
        font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(135deg, #C2FFD8 10%, #465EFB 100%);
            background-attachment: fixed;
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
      .main-content {
        margin-left: 250px;
      }
      .card {
        margin-bottom: 20px;
      }
      .card-container {
        background-color: #ffffff; /* Set parent container background to white */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 15px;
      }
      @media (max-width: 992px) {
  .main-content {
    margin-left: 0;
    padding: 20px;
  }
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

      <div class="col-lg offset-lg-3 mt-4 mt-lg-0 main-content">
      <div class="container mt-4">
        <h1 class="mt-5">Resident Records</h1>
        <div class="d-flex justify-content-end mb-3">
          <a href="add_resident.html" class="btn btn-primary" style="margin-right:20px">
            <i class="bi bi-plus-lg"></i> Add Resident
          </a>
          <a href="add_resident.html" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Print Records
          </a>
        </div>
<!-- Search Input Field -->
<input type="text" id="searchInput" class="form-control mb-3" placeholder="Search residents..." style="width:400px;">

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Birthday</th>
            <th>Zone</th>
            <th>Contact Number</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="residentTableBody">
        <?php
        include 'db.php';

        $sql = "SELECT resident_id AS id, CONCAT(first_name, ' ', middle_name, ' ', last_name, ' ', suffix_name) AS full_name, gender, date_of_birth, zone, contact_number FROM residents";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['full_name']}</td>
                            <td>{$row['gender']}</td>
                            <td data-dob='{$row['date_of_birth']}'></td>
                            <td>{$row['date_of_birth']}</td>
                            <td>{$row['zone']}</td>
                            <td>{$row['contact_number']}</td>
                           <td>
                              <a href='edit_resident.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                              <a href='#' class='btn btn-danger btn-sm' onclick='showDeleteModal({$row['id']});'>Delete</a>
                          </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No residents found</td></tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='text-center'>Error: " . $conn->error . "</td></tr>";
        }

        $conn->close();
        ?>
    </tbody>
</table>

      </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1050; display: flex; align-items: center; justify-content: center;">
      <div class="modal-content" style="padding: 20px; border: 2px solid #7AB2B2; border-radius: 10px; text-align: center; background-color: #f8f9fa; max-width: 400px; width: 100%;">
        <h3 style="margin-bottom: 15px;">Confirm Logout</h3>
        <p>Are you sure you want to log out?</p>
        <button onclick="window.location.href='../logout.php'" style="background-color: #4D869C; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Yes</button>
        <button onclick="hideLogoutModal()" style="background-color: #CDE8E5; color: black; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top:10px;">No</button>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this resident?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>


    <script>
      // Ensure the modal is hidden on page load
      document.addEventListener('DOMContentLoaded', function() {
        hideLogoutModal();
      });

      function showLogoutModal() {
        document.getElementById('logoutModal').style.display = 'flex';
      }

      function hideLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
      }
    </script>

    <!--This is the confirmation to delete-->
    <script type="text/javascript">
    function showDeleteModal(id) {
        // Set the href attribute of the confirmation button to the delete link
        document.getElementById('confirmDeleteBtn').href = 'delete_resident.php?id=' + id;
        // Show the modal
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>

  <!--This is to calculate the age dynamicaly and Search-->
  <script>
document.addEventListener("DOMContentLoaded", function() {
    const ageCells = document.querySelectorAll("td[data-dob]");
    
    ageCells.forEach(function(cell) {
        const dob = new Date(cell.getAttribute("data-dob"));
        const age = calculateAge(dob);
        cell.textContent = age;
    });

    function calculateAge(dob) {
        const diff = Date.now() - dob.getTime();
        const ageDate = new Date(diff);
        return Math.abs(ageDate.getUTCFullYear() - 1970);
    }

    // Search Functionality
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('residentTableBody');
    const tableRows = tableBody.getElementsByTagName('tr');

    searchInput.addEventListener('input', function() {
        const filter = searchInput.value.toLowerCase();
        Array.from(tableRows).forEach(function(row) {
            const fullName = row.cells[0].textContent.toLowerCase();
            const gender = row.cells[1].textContent.toLowerCase();
            const age = row.cells[2].textContent.toLowerCase();
            const birthday = row.cells[3].textContent.toLowerCase();
            const zone = row.cells[4].textContent.toLowerCase();
            const contactNumber = row.cells[5].textContent.toLowerCase();

            if (fullName.includes(filter) || gender.includes(filter) || age.includes(filter) || birthday.includes(filter) || zone.includes(filter) || contactNumber.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
