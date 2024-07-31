
<?php
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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['role']; // Get user type from form

    // Check if email already exists
    $sql = "SELECT * FROM accounts WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "An account with this email already exists!";
    } else {
        // Insert new account into the database
        $sql = "INSERT INTO accounts (first_name, last_name, email, password, user_type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $user_type);
        if ($stmt->execute()) {
            $message = "success";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
    $stmt->close();
}

$conn->close();
header("Location: Caccount.html?message=" . urlencode($message));
exit();
?>
