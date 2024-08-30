<?php
include 'db.php'; // Include the database connection

// Fetch Animal Bite Cases Data
$query = "SELECT longitude, latitude, COUNT(*) as count FROM residents r
          JOIN animal_bite a ON r.resident_id = a.resident_id
          GROUP BY longitude, latitude";
$result = $conn->query($query);
$animal_bite_data = [];

while ($row = $result->fetch_assoc()) {
    $animal_bite_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Bite Cases</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
</head>
<body>
    <div id="map" style="height: 500px; width: 100%;"></div>
    <script>
        const animalBiteData = <?php echo json_encode($animal_bite_data); ?>;
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: { lat: parseFloat(animalBiteData[0].latitude), lng: parseFloat(animalBiteData[0].longitude) }
        });

        animalBiteData.forEach(function (data) {
            new google.maps.Marker({
                position: { lat: parseFloat(data.latitude), lng: parseFloat(data.longitude) },
                map: map,
                title: `Cases: ${data.count}`
            });
        });
    </script>
</body>
</html>
