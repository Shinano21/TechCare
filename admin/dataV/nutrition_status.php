header('Content-Type: application/json');
include 'db.php'; // Include the database connection file

$query = "SELECT status, COUNT(*) as count FROM nutrition GROUP BY status";
$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
