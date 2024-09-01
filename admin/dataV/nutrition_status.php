<?php
include 'db.php'; // Include the database connection

// Fetch Nutrition Status Data
$query = "SELECT status, COUNT(*) as count FROM nutrition GROUP BY status";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$nutrition_data = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #f0f0f5, #d9d9e2);
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
            color: #4b4b7c;
        }

        canvas {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Nutrition Status of Residents</h1>
        <canvas id="nutritionStatusChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('nutritionStatusChart').getContext('2d');
            const nutritionData = <?php echo json_encode($nutrition_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
            const statuses = nutritionData.map(data => data.status);
            const counts = nutritionData.map(data => data.count);

            // Create gradient colors for the bars
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(153, 102, 255, 0.8)');
            gradient.addColorStop(1, 'rgba(153, 102, 255, 0.2)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: statuses,
                    datasets: [{
                        label: 'Number of Residents',
                        data: counts,
                        backgroundColor: gradient,
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
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
                                color: '#4b4b7c'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(153, 102, 255, 0.8)',
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
