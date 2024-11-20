<?php
session_start();
require_once 'dbdashboard.php'; //include dbdashboard

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) { //check if admin login
    header("Location: admin.php"); // redirect to login if not logged in
    exit();
}

$userDashboard = new AdminDashboard();;//call the admindashboard class from dbdashboard.php
$verifiedUsers = $userDashboard->getVerifiedUsers(); // fetch all verified users 
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
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Birthday</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Verification Code</th>
                    <th>Edit / Delete</th>
                </tr>
            </thead>
            <tbody>
            <?php
                // Check if there are results and output them
                if (!empty($verifiedUsers)) {
                    foreach ($verifiedUsers as $row) {
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
                        echo "<td>
                            <a href='edituser.php?id=" . $row['user_id'] . "' class='edit-btn'>Edit</a>
                            <a href='deleteuser.php?id=" . $row['user_id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this entry?\")'>Delete</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No data available</td></tr>";
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