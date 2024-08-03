<?php
include 'db.php';

$sql = "SELECT * FROM residents";
$result = $conn->query($sql);

$residents = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $residents[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($residents);

$conn->close();
?>
