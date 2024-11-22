<?php
session_start();
require_once 'dbadminchat.php'; 

$_SESSION['user'] = 'admin'; 
$admin_id = $_SESSION['admin_id']; 

$messageHandler = new AdminMessage($admin_id);
$messageHandler->messageSubmission();
$messages = $messageHandler->getMessages();

$userId = $messageHandler->getUserIds();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chat</title>
    <link rel="stylesheet" href="../css/adminchat.css">
</head>
<body>
    <?php include('../sidebar/adminsidebar.php');?>
    <section class="section">
        <div class="container">
            <?php if (!empty($userId)): ?>
                <?php foreach ($userId as $userId): ?>
                    <h1>User ID: <?php echo htmlspecialchars($userId); ?></h1>
                <?php endforeach; ?>
            <?php else: ?>
                <h1>No user IDs found.</h1>
            <?php endif; ?>
            <div id="messages">
                <?php // display admin and client messages
                    foreach ($messages as $msg) {
                        $class = ($msg['sender'] === 'admin') ? 'admin-message' : 'client-message';
                        echo "<div class='message $class'>{$msg['sender']}: {$msg['message']}</div>";
                    }
                ?>
            </div>
            <form method="post">
                <div class="field has-addons">
                    <div class="form-container">
                        <input class="input" type="text" name="adminMessage" placeholder="Type your message..." required />
                        <button class="button">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</body>
</html>