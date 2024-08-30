<?php
include 'db.php'; // Include the database connection

// Fetch Nutrition Status Data
$query = "SELECT status, COUNT(*) as count FROM nutrition GROUP BY status";
$result = $conn->query($query);
$nutrition_data = [];

while ($row = $result->fetch_assoc()) {
    $nutrition_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Status</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="nutritionStatusChart"></canvas>
    <script>
        const ctx = document.getElementById('nutritionStatusChart').getContext('2d');
        const nutritionData = <?php echo json_encode($nutrition_data); ?>;
        const statuses = nutritionData.map(data => data.status);
        const counts = nutritionData.map(data => data.count);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: statuses,
                datasets: [{
                    label: 'Residents',
                    data: counts,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
