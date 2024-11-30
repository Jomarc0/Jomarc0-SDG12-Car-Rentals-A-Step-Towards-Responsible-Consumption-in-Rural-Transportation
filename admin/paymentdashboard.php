<?php
session_start();
require_once 'dbdashboard.php'; //include dbdashboard

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) { //check if admin login
    header("Location: admin.php"); // redirect to login if not logged in
    exit();
}

$paymentDashboard = new AdminDashboard(); //call the admindashboard class from dbdashboard.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_id = $_POST['payment_id'];
    $action = $_POST['action'];
    $paymentDashboard->handlePayment($payment_id, $action);
}

$pendingRequests = $paymentDashboard->getPendingRequests();
$status = $paymentDashboard->fetchPendingPayments();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Payment Dashboard</title>
    <!-- datatable css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/admindashboard.css">
    <link rel="stylesheet" href="../css/adminpayment.css">
    
</head>
<body>
    <?php include('../sidebar/adminsidebar.php'); ?>
    <div class="container">
        <div class="cards">
            <div class="card">
                <div class="box">
                    <h1>Pending Requests</h1>
                    <h1><?php echo htmlspecialchars($pendingRequests);?></h1>
                </div>
                <div class="icon-case">
                        <img src="requests.png" alt="">
                    </div>
            </div>
        </div>
        <div class="dashboard">
            <table id="paymentsTable">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>User Email</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($status as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['payment_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="payment_id" value="<?php echo htmlspecialchars($row['payment_id']); ?>">
                                    <button type="submit" name="action" value="approve">Approve</button>
                                    <button type="submit" name="action" value="reject">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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