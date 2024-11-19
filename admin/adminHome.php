<?php
// Start the session
session_start();

// Include database connection
require_once __DIR__ . '/../dbcon/dbcon.php'; // Adjust the path as necessary

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php"); // Redirect to login if not logged in
    exit();
}

// Create a new database connection
$database = new Database();
$conn = $database->getConn(); // Get the database connection

// Fetch total users
$totalUsersQuery = "SELECT COUNT(*) as total FROM user"; // Replace 'users' with your actual users table name
$result = $conn->query($totalUsersQuery);
$totalUsers = $result->fetch_assoc()['total'];

// Fetch total rented cars
$totalRentedCarsQuery = "SELECT COUNT(*) as total FROM rentedcar WHERE rent_status = 'rented'"; // Replace 'rentedcar' with your actual rented cars table name
$result = $conn->query($totalRentedCarsQuery);
$totalRentedCars = $result->fetch_assoc()['total'];

// Fetch pending requests
$pendingRequestsQuery = "SELECT COUNT(*) as total FROM payment WHERE payment_status = 'pending'"; // Replace 'payment' with your actual payment table name
$result = $conn->query($pendingRequestsQuery);
$pendingRequests = $result->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- datatable css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- header css -->
    <link rel="stylesheet" href="../css/adminheader.css">
    <!-- admin css -->
    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>
        
    <?php include('adminHeader.php'); ?>

    <div class="container">
        <h2>Welcome to the Admin Dashboard</h2>

        <div class="stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <p><?php echo $totalUsers; ?></p> <!-- Dynamically fetched from the database -->
            </div>
            <div class=" stat-card">
                <h3>Total Rented Cars</h3>
                <p><?php echo $totalRentedCars; ?></p> <!-- Dynamically fetched from the database -->
            </div>
            <div class="stat-card">
                <h3>Pending Requests</h3>
                <p><?php echo $pendingRequests; ?></p> <!-- Dynamically fetched from the database -->
            </div>
        </div>

    <div class="table-container">
    <h3>Recent Activity</h3>
    <table id="admin-datatable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Birthday</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Verification Code</th>
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
            $query = "SELECT u.user_id, u.first_name, u.last_name, u.address, u.gender, u.dob, u.email, u.phone_number, u.verification_code,r.booking_area, r.destination, r.trip_date_time, r.return_date_time, r.vehicle_type, r.rent_status  FROM user AS u JOIN rentedcar AS r ON u.user_id = r.user_id"; // Adjust the table name as needed
            $result = $conn->query($query);

            // Check if there are results and output them
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['verification_code']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['booking_area']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['destination']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['trip_date_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['return_date_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['vehicle_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['rent_status']) . "</td>";
                    echo "<td>
                        <a href= 'edit_rentedcar.php?id=" .$row['user_id' ] . "' class='edit-btn' >Edit</a>
                        <a href='delete_rentedcar.php?id=" . $row['user_id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this entry?\")'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='15'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>

            <!-- datatable js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#recent-activity').DataTable();
        });
    </script>
</body>
</html>