population_growth.php
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '', 'your_database_name');

$query = "SELECT COUNT(*) as population, YEAR(date_of_birth) as year FROM residents GROUP BY year";
$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);


