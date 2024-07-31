<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ias2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = array('success' => false);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists
    $sql = "SELECT * FROM accounts WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['user_type'] = $row['user_type'];

            // Set response data
            $response['success'] = true;
            if ($row['user_type'] == 'admin') {
                $response['redirect'] = 'admin/dashboard.php';
            } elseif ($row['user_type'] == 'bhw') {
                $response['redirect'] = 'bhw/dashboard.php';
            } else {
                $response['redirect'] = 'landing.php';
            }
        } else {
            $response['error_message'] = "Invalid password!";
        }
    } else {
        $response['error_message'] = "No account found with that email!";
    }
    $stmt->close();
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
