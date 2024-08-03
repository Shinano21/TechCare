<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Residents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Residents</h1>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Birthday</th>
                <th>Zone</th>
                <th>Contact Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'db.php';
            
            $sql = "SELECT id, CONCAT(first_name, ' ', middle_name, ' ', last_name, ' ', suffix_name) AS full_name, gender, date_of_birth, zone, contact_number, TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age FROM residents";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['full_name']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['date_of_birth']}</td>
                            <td>{$row['zone']}</td>
                            <td>{$row['contact_number']}</td>
                            <td>
                                <a href='edit_resident.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_resident.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No residents found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
