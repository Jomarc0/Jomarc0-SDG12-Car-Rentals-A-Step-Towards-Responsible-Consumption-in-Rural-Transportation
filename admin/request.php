<?php
session_start();
require_once 'dbdashboard.php'; //include dbdashboard

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) { //check if admin login
    header("Location: admin.php"); // redirect to login if not logged in
    exit();
}

$adminDashboard = new AdminDashboard(); //call the admindashboard class from dbdashboard.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];
    $adminDashboard ->getverified($user_id, $action);
}

$pendingId =$adminDashboard->getPendingID();
$pendingID = $adminDashboard->getTotalID();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Dashboard</title>
    <!-- datatable css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/admindashboard.css">
    <link rel="stylesheet" href="../css/adminuser.css">
    
</head>
<body>
    <?php include('../sidebar/adminsidebar.php'); ?>
    <div class="container">
    <div class="cards">
            <div class="card">
                <div class="box">
                    <h1>Pending Requests</h1>
                    <h1><?php echo htmlspecialchars($pendingID);?></h1>
                </div>
                <div class="icon-case">
                        <img src="requests.png" alt="">
                    </div>
            </div>
        </div>
        <div class="dashboard">
            <h1>User List</h1>
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Date of Birth</th>
                        <th>Driver's License</th>
                        <th>ID Stayus</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($pendingId as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['dob']); ?></td>
                        <td>
                            <?php
                            // Check if the driver's license exists and is a valid URL
                            $driversLicense = htmlspecialchars($row['drivers_license'] ?? '');
                            if ($driversLicense) {
                                echo "<img src='$driversLicense' alt='Driver's License' style='width: 100px; 
                                        height: 100px;  
                                        border: 4px solid #4F5576;
                                        border-color: #4F5576; cursor: pointer;' 
                                        onclick=\"openModal('$driversLicense')\">";
                            } else {
                                echo "No driver's license available";
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['verified_id']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['user_id']); ?>">
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
    
<div id="imageModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.8);">
    <span onclick="closeModal()" style="color: white; font-size: 30px; position: absolute; right: 20px; top: 20px; cursor: pointer;">&times;</span>
    <img id="modalImage" style="margin: auto; display: block; max-width: 90%; max-height: 90%; position: relative; top: 50%; transform: translateY(-50%);">
</div>
    <!-- datatable js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable();
        });
    </script>
    <script>
        function openModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Close the modal when clicking outside of the image
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>