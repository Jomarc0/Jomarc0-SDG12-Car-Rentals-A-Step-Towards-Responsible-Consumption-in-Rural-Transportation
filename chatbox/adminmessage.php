<?php
session_start();
require_once 'Message.php'; 


$adminId = $_SESSION['admin_id'];  //to know admin is login

$userId = $_GET['user_id'] ?? null; //get the user id from user
$messageHandler = new Message($adminId, $userId);

$messageHandler->adminMessage(); //call this function to get the admin message

$search = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $search = htmlspecialchars($_POST['search']);
}

$users = $messageHandler->getUsers($search); //get all from search

$messages = $userId ? $messageHandler->getMessages($userId) : []; //get the message from selected usee
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chat</title>
    <link rel="stylesheet" href="../css/admindashboard.css">
    <link rel="stylesheet" href="../css/adminchatbox.css">

</head>
<body>
<?php include('../sidebar/adminsidebar.php'); ?>
    <div class="messenger-container">
        <!-- user like messenger  -->
        <div class="sidebar">
        <input type="text" name="search" placeholder="Search Users" class="search-bar" value="<?php echo htmlspecialchars($search); ?>">
        <ul class="user-list">
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <li>
                        <a href="?user_id=<?php echo htmlspecialchars($user['user_id']); ?>">
                            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="profile-pic">
                            <span class="user-name"><?php echo htmlspecialchars($user['name']); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="no-users">No users found.</li>
            <?php endif; ?>
        </ul>
        </div>

        <!-- Chat window -->
        <div id="chat-area">
            <?php if ($userId): ?>
                <div id="messages">
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $msg): //loop the message from user and admin?> 
                            <?php 
                                $class = ($msg['sender'] === 'admin') ? 'admin-message' : 'client-message'; 
                            ?>
                            <div class="message-bubble <?php echo $class; ?>">
                                <p class="message-text"><?php echo htmlspecialchars($msg['message']); ?></p>
                                <span class="message-time"><?php echo htmlspecialchars($msg['message_at']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No messages yet. Start the conversation!</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <h2>Select a user to start chatting</h2>
            <?php endif; ?>
        </div>

        <form method="POST" class="form-container"><!-- input box -->
            <textarea name="adminMessage" class="input" placeholder="Type your message..." required></textarea>
            <button type="submit" class="button">Send</button>
        </form>
        </div>
    </div>
</body>
</html>