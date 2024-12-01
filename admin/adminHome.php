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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/admindashboard.css">
    <title>Admin Panel</title>
</head>
<body>
    <?php include('../sidebar/adminsidebar.php');?>
    
    <div class="container">
        <div class="header">
        <div class="cards">
                <div class="card">
                    <div class="box">
                        <h1>Total Users</h1>
                        <h1><?php echo htmlspecialchars($totalUsers); ?></h1>
                    </div>
                    <div class="icon-case">
                        <img src="users.png" alt="">
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <h1>Total Rented Cars</h1>
                        <h1><?php echo htmlspecialchars($totalRentedCars); ?></h1>
                    </div>
                    <div class="icon-case">
                        <img src="rents.png" alt="">
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <h1>Pending Requests</h1>
                        <h1><?php echo htmlspecialchars($pendingRequests); ?></h1>
                    </div>
                    <div class="icon-case">
                        <img src="requests.png" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="content-2">
                <div class="recent-payments">
                    <div class="title">
                        <h2>Recent Activity</h2>
                    </div>
                    <table>
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
                                echo "<tr><td colspan='13'>No data available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- datatable js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#paymentsTable').DataTable();
        });
    </script>
</body>
</html>