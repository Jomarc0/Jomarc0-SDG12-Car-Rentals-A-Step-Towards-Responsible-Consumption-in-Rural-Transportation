<?php
require_once __DIR__ . '/../dbcon/dbcon.php';
require_once 'paymentHandler.php'; // Include the PaymentHandler class

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
$stmt = $conn->prepare("SELECT p.*, u.email as user_email FROM payment as p JOIN user as u ON p.user_id = u.user_id WHERE p.payment_status = 'pending'");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Payment Dashboard</title>
    <!-- datatable css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/admintable.css">
    <style>
        /* styles.css */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
        }

        .dashboard {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 5px;
        }

        button[name="action"][value="approve"] {
            background-color: #27ae60;
            color: white;
        }

        button[name="action"][value="reject"] {
            background-color: #e74c3c;
            color: white;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .side-menu {
                width: 200px;
            }

            .container {
                margin-left: 200px;
            }
        }
    </style>
</head>
<body>
    <?php include('../sidebar/adminsidebar.php'); ?>
    <div class="container">
        <div class="dashboard">
            <h2>Pending Payments</h2>
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
                    <?php foreach ($result as $row): ?>
                        <tr>
                            < td><?php echo htmlspecialchars($row['payment_id']); ?></td>
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