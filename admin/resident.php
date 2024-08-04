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
    <style>
      body {
        font-family: Arial, sans-serif;
        background-image: linear-gradient(135deg, #C2FFD8 10%, #465EFB 100%);
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
      .sidebar a {
        padding: 10px 15px;
        text-decoration: none;
        font-size: 18px;
        color: #333;
        display: block;
      }
      .sidebar a i {
        margin-right: 10px;
        font-size: 20px;
      }
      .sidebar a:hover {
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
    </style>
  </head>
  <body>
    <div class="sidebar text-center">
      <div class="text-center mb-5">
        <img src="images/LOGO.svg" alt="TechCare" width="100">
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

    <div class="main-content">
      <nav class="navbar navbar-light bg-light border-bottom border-dark rounded-bottom">
        <div class="container-fluid">
          <span class="navbar-brand mb-0 h1 bi bi-geo-alt-fill">Barangay Estanza</span>
          <span class="navbar-text bi bi-person-check">
            Admin User
          </span>
        </div>
      </nav>

      <div class="container mt-4">
        <h1 class="mt-5">Residents</h1>
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
            <tbody>
                <?php
                include 'db.php';

                $sql = "SELECT resident_id AS id, CONCAT(first_name, ' ', middle_name, ' ', last_name, ' ', suffix_name) AS full_name, gender, date_of_birth, zone, contact_number, TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age FROM residents";
                $result = $conn->query($sql);

                if ($result) {  // Check if the query was successful
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['full_name']}</td>
                                    <td>{$row['gender']}</td>
                                    <td>{$row['age']}</td>
                                    <td>{$row['date_of_birth']}</td>
                                    <td>{$row['zone']}</td>
                                    <td>{$row['contact_number']}</td>
                                    <td>
                                        <a href='edit_resident.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='delete_resident.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No residents found</td></tr>";
                    }
                } else {
                    // Query failed
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
