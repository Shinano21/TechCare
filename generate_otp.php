<?php
session_start();
header("Content-Security-Policy: default-src 'self'");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// Include PHPMailer classes
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "techcare";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        header("Location: Forgot.html?error=" . urlencode("Connection failed: " . $conn->connect_error));
        exit();
    }

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        header("Location: Forgot.html?error=" . urlencode("Invalid email format"));
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $otp = generateOTP();

        $stmt = $conn->prepare("INSERT INTO otp (email, otp_number, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $email, $otp);
        $stmt->execute();
        $stmt->close();

        $_SESSION['otp_email'] = $email;

        // Send OTP via email
        if (sendOTPEmail($email, $otp)) {
            header("Location: otp.html?email=" . urlencode($email));
            exit();
        } else {
            header("Location: Forgot.html?error=" . urlencode("Failed to send OTP email."));
            exit();
        }
    } else {
        header("Location: Forgot.html?error=" . urlencode("Email not found in our database."));
        exit();
    }

    $conn->close();
}

function generateOTP() {
    return mt_rand(100000, 999999);
}

function sendOTPEmail($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'janjan9925@gmail.com';                 //SMTP username
        $mail->Password   = 'zmzjauxlivhjqmrm';                     //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to

        //Recipients
        $mail->setFrom('janjan9925@gmail.com', 'TechCare');
        $mail->addAddress($email);                                  //Add a recipient

        //Content
        $mail->isHTML(true);                                        //Set email format to HTML
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP code is <b>$otp</b>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
