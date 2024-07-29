<?php
ini_set('session.gc_maxlifetime', 600);
ini_set('session.cookie_lifetime', 600);
session_start();

header("Content-Security-Policy: default-src 'self'");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Content-Type: application/json");

$response = ["success" => false, "message" => "An error occurred."];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ias2";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        $response["message"] = "Connection failed: " . $conn->connect_error;
        echo json_encode($response);
        exit();
    }

    if (isset($_SESSION['otp_email'])) {
        $email = $_SESSION['otp_email'];
        $otp_inputs = $_POST['otp'];
        $entered_otp = implode("", $otp_inputs);

        $stmt = $conn->prepare("SELECT * FROM otp WHERE email = ? AND otp_number = ? AND created_at >= NOW() - INTERVAL 10 MINUTE");
        $stmt->bind_param("ss", $email, $entered_otp);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['reset_email'] = $email;
            $response["success"] = true;
            $response["message"] = "OTP verified.";
        } else {
            $response["message"] = "Invalid OTP code. Please try again.";
        }
    } else {
        $response["message"] = "Session expired or email not set.";
    }

    $conn->close();
} else {
    $response["message"] = "Invalid request method.";
}

echo json_encode($response);
?>
