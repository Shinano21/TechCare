header('Content-Type: application/json');
include 'db.php'; // Include the database connection file

$query = "SELECT vaccine_name, COUNT(*) as count FROM immunization GROUP BY vaccine_name";
$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);