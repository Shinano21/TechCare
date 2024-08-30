<?php
include 'db.php'; // Include the database connection

// Fetch Immunization Data
$query = "SELECT vaccine_name, COUNT(*) as count FROM immunization GROUP BY vaccine_name";
$result = $conn->query($query);
$immunization_data = [];

while ($row = $result->fetch_assoc()) {
    $immunization_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Immunization Coverage</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="immunizationCoverageChart"></canvas>
    <script>
        const ctx = document.getElementById('immunizationCoverageChart').getContext('2d');
        const immunizationData = <?php echo json_encode($immunization_data); ?>;
        const vaccineNames = immunizationData.map(data => data.vaccine_name);
        const counts = immunizationData.map(data => data.count);

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: vaccineNames,
                datasets: [{
                    data: counts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });
    </script>
</body>
</html>
