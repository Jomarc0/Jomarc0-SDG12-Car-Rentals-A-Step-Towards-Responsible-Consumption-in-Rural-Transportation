<?php
session_start();
require_once 'dbdashboard.php'; //include dbdashboard

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) { //check if admin login
    header("Location: admin.php"); // redirect to login if not logged in
    exit();
}

$adminDashboard = new AdminDashboard(); //call the admindashboard class from dbdashboard.php
$rentedCars = $adminDashboard->getRentedCars();
$totalRentedCars = $adminDashboard->getTotalRentedCars();
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
    <link rel="stylesheet" href="../css/admindashboard.css">
    <link rel="stylesheet" href="../css/userandrent.css">

</head>

<body>
    <?php include('../sidebar/adminsidebar.php'); ?>

    <div class="container">
        <div class="header">
            <div class="cards">
                <div class="card">
                        <div class="box">
                            <h1>Total Rented Cars</h1>
                            <h1><?php echo htmlspecialchars($totalRentedCars); ?></h1>
                        </div>
                        <div class="icon-case">
                            <img src="rents.png" alt="">
                        </div>
                </div>
            </div>
        </div>
        <table id="admin-datatable" class="display">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Rent ID</th>
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
            if (!empty($rentedCars)) {
                foreach ($rentedCars as $rent) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($rent['user_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($rent['rent_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($rent['booking_area']) . "</td>";
                    echo "<td>" . htmlspecialchars($rent['destination']) . "</td>";
                    echo "<td>" . htmlspecialchars($rent['trip_date_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($rent['return_date_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($rent['vehicle_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($rent['rent_status']) . "</td>";
                    echo "<td>
                        <a href='editrent.php?id=" . $rent['rent_id'] . "' class='edit-btn'>Edit</a>
                        <a href='deleterent.php?id=" . $rent['rent_id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this entry?\")'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No data available</td></tr>";
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