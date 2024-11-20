<?php
session_start();
require_once 'dbdashboard.php'; //include dbdashboard

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) { //check if admin login
    header("Location: admin.php"); // redirect to login if not logged in
    exit();
}

$adminDashboard = new AdminDashboard();//call the admindashboard class from dbdashboard.php
//get the data in functions
$totalUsers = $adminDashboard->getTotalUsers();
$totalRentedCars = $adminDashboard->getTotalRentedCars();
$pendingRequests = $adminDashboard->getPendingRequests();
$recentActivity = $adminDashboard->getRecentActivity();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/adminheader.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <?php include('adminHeader.php'); ?>

    <div class="container">
        <h2>Welcome to the Admin Dashboard</h2>

        <div class="stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <p><?php echo htmlspecialchars($totalUsers); ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Rented Cars</h3>
                <p><?php echo htmlspecialchars($totalRentedCars); ?></p>
            </div>
            <div class="stat-card">
                <h3>Pending Requests</h3>
                <p><?php echo htmlspecialchars($pendingRequests); ?></p>
            </div>
        </div>

        <div class="table-container">
            <h3>Recent Activity</h3>
            <table id="admin-datatable" class="display">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Address</th>
                        <th>Gender</th>
                        <th>Birthday</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Booking Area</th>
                        <th>Destination</th>
                        <th>Trip Date & Time</th>
                        <th>Return Date & Time</th>
                        <th>Vehicle Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Output recent activity data
                    if (!empty($recentActivity)) {
                        foreach ($recentActivity as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['booking_area']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['destination']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['trip_date_time']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['return_date_time']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['vehicle_type']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='15'>No data available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#admin-datatable').DataTable();
                });
            </script>
        </div>s
    </div>
</body>
</html>