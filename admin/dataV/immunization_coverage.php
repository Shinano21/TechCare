<?php
// Include the database connection using MySQLi
include 'db.php';

// Fetch Immunization Data
$query = "SELECT vaccine_name, COUNT(*) as count FROM immunization GROUP BY vaccine_name";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    body {
        background: linear-gradient(135deg, #f8f9fa, #e3e4e8);
        font-family: Arial, sans-serif;
        color: #333;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
    }

    .container {
        max-width: 800px;
        padding: 20px;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        margin-bottom: 40px;
        font-size: 2rem;
    }

    canvas {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>


</head>

<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center mb-4">Immunization Coverage</h1>
        <canvas id="immunizationCoverageChart"></canvas>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('immunizationCoverageChart').getContext('2d');
        const immunizationData = <?php echo json_encode($immunization_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        const vaccineNames = immunizationData.map(data => data.vaccine_name);
        const counts = immunizationData.map(data => data.count);

        // Create gradient colors
        const gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
        gradient1.addColorStop(0, 'rgba(255, 99, 132, 0.8)');
        gradient1.addColorStop(1, 'rgba(255, 99, 132, 0.2)');

        const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
        gradient2.addColorStop(0, 'rgba(54, 162, 235, 0.8)');
        gradient2.addColorStop(1, 'rgba(54, 162, 235, 0.2)');

        const gradient3 = ctx.createLinearGradient(0, 0, 0, 400);
        gradient3.addColorStop(0, 'rgba(255, 206, 86, 0.8)');
        gradient3.addColorStop(1, 'rgba(255, 206, 86, 0.2)');

        const gradient4 = ctx.createLinearGradient(0, 0, 0, 400);
        gradient4.addColorStop(0, 'rgba(75, 192, 192, 0.8)');
        gradient4.addColorStop(1, 'rgba(75, 192, 192, 0.2)');

        const gradient5 = ctx.createLinearGradient(0, 0, 0, 400);
        gradient5.addColorStop(0, 'rgba(153, 102, 255, 0.8)');
        gradient5.addColorStop(1, 'rgba(153, 102, 255, 0.2)');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: vaccineNames,
                datasets: [{
                    data: counts,
                    backgroundColor: [gradient1, gradient2, gradient3, gradient4, gradient5],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.label}: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

</body>

</html>
