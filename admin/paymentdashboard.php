<?php
require_once __DIR__ . '/../dbcon/dbcon.php';
require_once 'PaymentHandler.php'; // Include the PaymentHandler class

try {
    $database = new Database();
    $conn = $database->getConn(); // to get database connection
} catch (Exception $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

// Approve or Reject Payment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_id = $_POST['payment_id'];
    $action = $_POST['action'];

    $paymentHandler = new PaymentHandler($conn);
    $paymentHandler->handlePayment($payment_id, $action);
}

// Fetch Pending Payments
$result = $conn->query("SELECT p.*, u.email as user_email FROM payment as p JOIN user as u ON p.user_id = u.user_id WHERE p.payment_status = 'pending'");
?>

<!DOCTYPE html <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Payment Dashboard</title>
    <!-- datatable css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/adminpayment.css">

</head>
<body>
    <?php include('adminHeader.php'); ?>
    <div class="dashboard">
        <h1>Pending Payments</h1>
        <table id="paymentsTable">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>User Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['payment_id']; ?></td>
                        <td><?php echo $row['user_email']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['payment_status']; ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="payment_id" value="<?php echo $row['payment_id']; ?>">
                                <button type="submit" name="action" value="approve">Approve</button>
                                <button type="submit" name="action" value="reject">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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