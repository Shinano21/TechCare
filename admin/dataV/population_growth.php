header('Content-Type: application/json');
include 'db.php'; // Include the database connection file

$query = "SELECT COUNT(*) as population, YEAR(date_of_birth) as year FROM residents GROUP BY year";
$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);