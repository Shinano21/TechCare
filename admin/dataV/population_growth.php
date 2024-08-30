<?php
include 'db.php'; // Include the database connection

// Fetch Population Growth Data
$query = "SELECT COUNT(*) as population, YEAR(date_of_birth) as year FROM residents GROUP BY year";
$result = $conn->query($query);
$population_data = [];

while ($row = $result->fetch_assoc()) {
    $population_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Population Growth Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="populationGrowthChart"></canvas>
    <script>
        const ctx = document.getElementById('populationGrowthChart').getContext('2d');
        const populationData = <?php echo json_encode($population_data); ?>;
        const years = populationData.map(data => data.year);
        const populations = populationData.map(data => data.population);

        new Chart(ctx, {
            type: 'line', // or 'bar'
            data: {
                labels: years,
                datasets: [{
                    label: 'Population',
                    data: populations,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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
