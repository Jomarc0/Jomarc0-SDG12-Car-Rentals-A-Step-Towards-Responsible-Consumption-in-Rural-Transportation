<?php 
require_once __DIR__ . '/../dbcon/dbcon.php';

try {
    $database = new Database();
    $conn = $database->getConn(); // to get database connection
} catch (Exception $exception) {
    die("Database connection failed: " . $exception->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- admin css -->
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <?php include('adminHeader.php'); ?>

    <div class="container">
        <h2>Admin Dashboard</h2>
        <table id="admin-datatable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ren ID</th>
                    <th>Booking Area</th>
                    <th>Destination</th>
                    <th>Trip Date & Time</th>
                    <th>Return Date & Time</th>
                    <th>Vehicle Type</th>
                    <th>Rent Status</th>
                    <th>Edit / Delete</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetch data from the database
            $query = "SELECT user_id, rent_id, booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_status FROM rentedcar"; // Adjust the table name as needed
            $result = $conn->query($query);

            // Check if there are results and output them
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['rent_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['booking_area']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['destination']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['trip_date_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['return_date_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['vehicle_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['rent_status']) . "</td>";
                    echo "<td>
                        <a href= 'dbupdate.php?id=" .$row['user_id' ] . "' class='edit-btn' >Edit</a>
                        <a href='dbdelete.php?id=" . $row['user_id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this entry?\")'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='15'>No data available</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Initialization -->
    <script>
        $(document).ready(function () {
            $('#admin-datatable').DataTable();
        });
    </script>

</body>

</html>