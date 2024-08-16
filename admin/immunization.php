<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resident_id = $_POST['resident_id'];
    $vaccine_name = $_POST['vaccine_name'];
    $immunization_type = $_POST['immunization_type'];
    $immunization_date = $_POST['immunization_date'];
    $immunization_location = $_POST['immunization_location'];
    $immunization_status = $_POST['immunization_status'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO immunization (resident_id, vaccine_name, immunization_type, immunization_date, immunization_location, immunization_status, notes)
            VALUES ('$resident_id', '$vaccine_name', '$immunization_type', '$immunization_date', '$immunization_location', '$immunization_status', '$notes')";

    if (mysqli_query($conn, $sql)) {
        $message = "New record created successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Immunization Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <style>
            body {
        font-family: 'Poppins', sans-serif;
        background-image: linear-gradient( 135deg, #C2FFD8 10%, #465EFB 100%);
      }
   /* Center the form on the page and style it */
   .main-content {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* Use min-height to allow for dynamic content */
    padding: 20px;
    margin-top: 60px; /* Add space to avoid overlap with the fixed sidebar */
    margin-right:70px;
}

    form {
        background-color: #ffffff; /* White background for the form */
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px; /* Limit the form width */
    }

    /* Arrange fields in a column */
    form label, form input, form select, form textarea {
        display: block;
        width: 100%;
        margin-bottom: 15px;
    }

    /* Styling the inputs and selects */
    form input, form select, form textarea {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    form input[type="submit"] {
        background-color: #465efb;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    form input[type="submit"]:hover {
        background-color: #3a4cc0;
    }

    .message {
        text-align: center;
        margin-bottom: 20px;
        color: green;
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
    <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
    <script>
        $(document).ready(function() {
            $('#resident_id').change(function() {
                var resident_id = $(this).val();
                if (resident_id) {
                    $.ajax({
                        type: 'POST',
                        url: 'fetch_resident.php',
                        data: {resident_id: resident_id},
                        dataType: 'json',
                        success: function(response) {
                            if (!response.error) {
                                $('#resident_info').html(`
                                    <p>Name: ${response.first_name} ${response.middle_name} ${response.last_name} ${response.suffix_name}</p>
                                    <p>Gender: ${response.gender}</p>
                                    <p>Date of Birth: ${response.date_of_birth}</p>
                                    <p>Place of Birth: ${response.place_of_birth}</p>
                                    <p>Religion: ${response.religion}</p>
                                    <p>Citizenship: ${response.citizenship}</p>
                                    <p>Street: ${response.street}</p>
                                    <p>Zone: ${response.zone}</p>
                                    <p>Barangay: ${response.barangay}</p>
                                    <p>Municipal: ${response.municipal}</p>
                                    <p>Province: ${response.province}</p>
                                    <p>Contact Number: ${response.contact_number}</p>
                                    <p>Education: ${response.education}</p>
                                    <p>Occupation: ${response.occupation}</p>
                                    <p>Civil Status: ${response.civil_status}</p>
                                `);
                            } else {
                                $('#resident_info').html('<p>Resident not found</p>');
                            }
                        }
                    });
                } else {
                    $('#resident_info').html('');
                }
            });
        });
    </script>
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

    <!--Main content-->
      <div class="col-lg offset-lg-3 mt-4 mt-lg-0 main-content">
            
            <?php if (isset($message)): ?>
                <p class="message"><?php echo $message; ?></p>
                <?php endif; ?>
                
                <form method="post" action="" >
            <h1>Add Immunization Record</h1>
            <hr>
            <label for="resident_id">Resident ID:</label>
            <select id="resident_id" name="resident_id" required>
                <option value="">Select Resident</option>
                <?php
                $result = mysqli_query($conn, "SELECT resident_id, CONCAT(first_name, ' ', last_name) AS name FROM residents");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['resident_id'] . "'>" . $row['name'] . "</option>";
                }
                ?>
            </select>

            <div id="resident_info">
                <!-- Resident info will be displayed here -->
            </div>

            <label for="vaccine_name">Vaccine Name:</label>
            <input type="text" id="vaccine_name" name="vaccine_name" required>

            <label for="immunization_type">Immunization Type:</label>
            <select id="immunization_type" name="immunization_type" required>
                <option value="Routine">Routine</option>
                <option value="Emergency">Emergency</option>
            </select>

            <label for="immunization_date">Immunization Date:</label>
            <input type="date" id="immunization_date" name="immunization_date" required>

            <label for="immunization_location">Immunization Location:</label>
            <input type="text" id="immunization_location" name="immunization_location" required>

            <label for="immunization_status">Immunization Status:</label>
            <select id="immunization_status" name="immunization_status" required>
                <option value="Completed">Completed</option>
                <option value="Pending">Pending</option>
            </select>

            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes" rows="4"></textarea>

            <input type="submit" value="Submit">
        </form>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
