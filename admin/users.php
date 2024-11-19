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
                // Fetch data from the database
                $query = "SELECT user_id, first_name, last_name, address, gender, dob, email, phone_number, verification_code FROM user WHERE verified = 1"; // Adjust the table name as needed
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
                        echo "<td>
                            <a href= 'dbupdate.php?id=" .$row['user_id' ] . "' class='edit-btn' >Edit</a>
                            <a href='dbdelete.php?id=" . $row['user_id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this entry?\")'>Delete</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='15'>No data available</td></tr>";
                }

                // No need to close the connection here, it will be handled by the destructor
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