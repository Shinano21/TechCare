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
            padding: 20px;
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
        .create-account-container {
            display: flex;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            background-color: #ffffff;
            max-width: 800px; /* Adjust this value as needed */
            width: 90%;
            height: 550px; /* Adjust this value as needed */
        }
        .create-account-image {
            flex: 1;
            overflow: hidden;
        }
        .create-account-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .create-account-form {
            padding: 40px;
            flex: 1;
        }
        .create-account-form h2 {
            margin-bottom: 20px;
            font-size: 30px;
            color: #333;
            font-weight: lighter;
        }
        .create-account-form p {
            margin-bottom: 10px;
            color: #666;
        }
        .create-account-form input[type="text"],
        .create-account-form input[type="email"],
        .create-account-form input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .create-account-form .button-container {
            text-align: center;
        }
        .create-account-form button {
            background-color: #4d869c;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%; /* Adjust this value as needed */
        }
        .create-account-form button:hover {
            background-color: #006f99;
        }
        .create-account-form .login-link {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
        }
        .create-account-form .login-link a {
            color: #008cba;
            text-decoration: none;
        }
        .create-account-form .login-link a:hover {
            text-decoration: underline;
        }
        .error-message,
        .success-message {
            color: red;
            margin-bottom: 10px;
            font-size: 12px;
        }
        .success-popup {
            font-family: "Poppins", sans-serif;
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f0fff0;
            border: 8px solid #0aff44;
            padding: 40px; /* Increased padding for larger size */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
            width: 30%; /* Added width for larger size */
            max-width: 600px; /* Added max-width for responsiveness */
        }
        .success-popup h2 {
            color: #28a745;
            font-size: 35px;
        }
        .success-popup a {
            color: #28a745;
            text-decoration: none;
        }
        .success-popup p {
            font-size: 20px;
            color: #333;
        }
        .success-popup button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .show-password {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .show-password label {
            font-size: 12px; /* Adjust the font size here */
            font-weight: lighter; /* You can also adjust the font weight */
        }
        .show-password input {
            margin-left: 10px;
        }
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
        input[type="password"]::-webkit-contacts-auto-fill-button,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            display: none !important;
        }
    </style>
    <script>
        function validateForm() {
            const password = document.forms["createAccount"]["password"].value;
            const email = document.forms["createAccount"]["email"].value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const passwordRegex =
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            const errorMessage = document.getElementById("error-message");

            if (!emailRegex.test(email)) {
                errorMessage.innerText = "Invalid email address.";
                return false;
            }

            if (!passwordRegex.test(password)) {
                errorMessage.innerText =
                    "Password should be at least 8 characters and contain an uppercase letter, a lowercase letter, a number, and a special character.";
                return false;
            }

            return true;
        }

        function togglePasswordVisibility() {
            const passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }

        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get("message");

            if (message === "success") {
                document.getElementById("success-popup").style.display = "block";
            } else if (message) {
                document.getElementById("error-message").innerText = message;
            }
        };
    </script>
</head>
<body>
<div class="sidebar text-center">
    <div class="text-center mb-5">
        <img src="images/LOGO.svg" alt="TechCare" width="100">
    </div>
    <a href="#"><i class="bi bi-speedometer2"></i>Dashboard</a>
    <a href="#"><i class="bi bi-people"></i>Residents</a>

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

    <a href="#"><i class="bi bi-file-earmark-medical"></i>Medical History</a>
    <a href="#"><i class="bi bi-calendar"></i>Appointments</a>
    <a href="#"><i class="bi bi-clipboard-data"></i>Reports</a>
    <a href="#"><i class="bi bi-clock-history"></i>Reminders</a>
    <a href="#"><i class="bi bi-gear"></i>Settings</a>
    <a href="#" onclick="showLogoutModal()"><i class="bi bi-box-arrow-right"></i>Logout</a>
</div>

<div class="main-content">
    <div class="card-container">
        <h1>Dashboard</h1>
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Services</h2>
                <div class="create-account-container">
                    <div class="create-account-image">
                        <img src="images/manWithLaptop.jpg" alt="Working on a laptop" />
                    </div>
                   <div class="create-account-form">
    <h2>Create Account</h2>
    <p>Fill in your details to create an account</p>
    <form name="createAccount" action="acreate.php" method="post" onsubmit="return validateForm()">
        <input type="text" name="first_name" placeholder="First Name" required />
        <input type="text" name="last_name" placeholder="Last Name" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" id="password" placeholder="Password" required />

        <!-- Role Dropdown -->
        <label for="role">Select Role:</label>
        <select name="role" id="role" required>
            <option value="" disabled selected>Select a role</option>
            <option value="bhw">BHW</option>
            <option value="admin">Admin</option>
        </select>

        <div id="error-message" class="error-message"></div>
        <div class="show-password">
            <label for="show-password">Show Password</label>
            <input type="checkbox" id="show-password" onclick="togglePasswordVisibility()" />
        </div>
        <div class="button-container">
            <button type="submit">Create Account</button>
        </div>
        <div class="login-link">
            <p>Already have an account? <a href="index.html">Login</a></p>
        </div>
    </form>
</div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1050; display: flex; align-items: center; justify-content: center;">
    <div class="modal-content" style="padding: 20px; border: 2px solid #7AB2B2; border-radius: 10px; text-align: center; background-color: #f8f9fa; max-width: 400px; width: 100%;">
        <h3 style="margin-bottom: 15px;">Confirm Logout</h3>
        <p>Are you sure you want to log out?</p>
        <button onclick="window.location.href='logout.php'" style="background-color: #4D869C; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Yes</button>
        <button onclick="hideLogoutModal()" style="background-color: #CDE8E5; color: black; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top:10px;">No</button>
    </div>
</div>

<script>
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
