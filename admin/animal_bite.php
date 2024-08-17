<?php
include 'db.php'; // Include the database connection

// Fetch all records from the animal_bite table
$sql = "SELECT * FROM animal_bite";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Bite Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Animal Bite Records Dashboard</h2>

<a href="add_animal_bite.php">Add New Record</a><br><br>

<table>
    <tr>
        <th>ID</th>
        <th>Resident ID</th>
        <th>Date of Exposure</th>
        <th>Nature of Bite</th>
        <th>Category of Bite</th>
        <th>Actions Taken</th>
        <th>Action</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['anibite_id'] . "</td>
                    <td>" . $row['resident_id'] . "</td>
                    <td>" . $row['date_of_exposure'] . "</td>
                    <td>" . $row['nature_of_bite'] . "</td>
                    <td>" . $row['category_of_bite'] . "</td>
                    <td>" . $row['actions_taken'] . "</td>
                    <td>
                        <a href='edit_animal_bite.php?anibite_id=" . $row['anibite_id'] . "'>Edit</a> |
                        <a href='delete_animal_bite.php?anibite_id=" . $row['anibite_id'] . "' onclick=\"return confirm('Are you sure you want to delete this record?');\">Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No records found</td></tr>";
    }
    ?>
</table>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
