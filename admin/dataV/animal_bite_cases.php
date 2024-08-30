header('Content-Type: application/json');
include 'db.php'; // Include the database connection file

$query = "SELECT longitude, latitude, COUNT(*) as count FROM residents r
          JOIN animal_bite a ON r.resident_id = a.resident_id
          GROUP BY longitude, latitude";
$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);