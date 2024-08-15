<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resident_id = $_POST['resident_id'];
    $vaccine_name = $_POST['vaccine_name'];
    $immunization_type = $_POST['immunization_type'];
    $immunization_date = $_POST['immunization_date'];
    $immunization_location = $_POST['immunization_location'];
    $immunization_status = $_POST['immunization_status'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO immunization (resident_id, vaccine_name, immunization_type, immunization_date, immunization_location, immunization_status, notes)
            VALUES ('$resident_id', '$vaccine_name', '$immunization_type', '$immunization_date', '$immunization_location', '$immunization_status', '$notes')";

    if (mysqli_query($conn, $sql)) {
        $message = "New record created successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Immunization Record</title>
    <style>
        /* Same styling as before */
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#resident_id').change(function() {
                var resident_id = $(this).val();
                if (resident_id) {
                    $.ajax({
                        type: 'POST',
                        url: 'fetch_resident.php',
                        data: {resident_id: resident_id},
                        dataType: 'json',
                        success: function(response) {
                            if (!response.error) {
                                $('#resident_info').html(`
                                    <p>Name: ${response.first_name} ${response.middle_name} ${response.last_name} ${response.suffix_name}</p>
                                    <p>Gender: ${response.gender}</p>
                                    <p>Date of Birth: ${response.date_of_birth}</p>
                                    <p>Place of Birth: ${response.place_of_birth}</p>
                                    <p>Religion: ${response.religion}</p>
                                    <p>Citizenship: ${response.citizenship}</p>
                                    <p>Street: ${response.street}</p>
                                    <p>Zone: ${response.zone}</p>
                                    <p>Barangay: ${response.barangay}</p>
                                    <p>Municipal: ${response.municipal}</p>
                                    <p>Province: ${response.province}</p>
                                    <p>Contact Number: ${response.contact_number}</p>
                                    <p>Education: ${response.education}</p>
                                    <p>Occupation: ${response.occupation}</p>
                                    <p>Civil Status: ${response.civil_status}</p>
                                `);
                            } else {
                                $('#resident_info').html('<p>Resident not found</p>');
                            }
                        }
                    });
                } else {
                    $('#resident_info').html('');
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Add Immunization Record</h2>

        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <label for="resident_id">Resident ID:</label>
            <select id="resident_id" name="resident_id" required>
                <option value="">Select Resident</option>
                <?php
                $result = mysqli_query($conn, "SELECT resident_id, CONCAT(first_name, ' ', last_name) AS name FROM residents");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['resident_id'] . "'>" . $row['name'] . "</option>";
                }
                ?>
            </select>

            <div id="resident_info">
                <!-- Resident info will be displayed here -->
            </div>

            <label for="vaccine_name">Vaccine Name:</label>
            <input type="text" id="vaccine_name" name="vaccine_name" required>

            <label for="immunization_type">Immunization Type:</label>
            <select id="immunization_type" name="immunization_type" required>
                <option value="Routine">Routine</option>
                <option value="Emergency">Emergency</option>
            </select>

            <label for="immunization_date">Immunization Date:</label>
            <input type="date" id="immunization_date" name="immunization_date" required>

            <label for="immunization_location">Immunization Location:</label>
            <input type="text" id="immunization_location" name="immunization_location" required>

            <label for="immunization_status">Immunization Status:</label>
            <select id="immunization_status" name="immunization_status" required>
                <option value="Completed">Completed</option>
                <option value="Pending">Pending</option>
            </select>

            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes" rows="4"></textarea>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
