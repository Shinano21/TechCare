<?php
include 'db.php'; // Include the database connection

// Fetch Population Growth Data
$query = "SELECT COUNT(*) as population, YEAR(date_of_birth) as year FROM residents GROUP BY year";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$population_data = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Population Growth Chart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #b9fbc0);
            font-family: Arial, sans-serif;
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .container {
            max-width: 900px;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            color: #00695c;
        }

        canvas {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Population Growth Over Years</h1>
        <canvas id="populationGrowthChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('populationGrowthChart').getContext('2d');
            const populationData = <?php echo json_encode($population_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
            const years = populationData.map(data => data.year);
            const populations = populationData.map(data => data.population);

            // Create gradient color for the line
            const gradientStroke = ctx.createLinearGradient(0, 0, 0, 400);
            gradientStroke.addColorStop(0, 'rgba(75, 192, 192, 0.8)');
            gradientStroke.addColorStop(1, 'rgba(75, 192, 192, 0.2)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: years,
                    datasets: [{
                        label: 'Population',
                        data: populations,
                        backgroundColor: gradientStroke,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        tension: 0.4 // Smooth the line
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            grid: {
                                borderDash: [5, 5]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#00695c'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(75, 192, 192, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
