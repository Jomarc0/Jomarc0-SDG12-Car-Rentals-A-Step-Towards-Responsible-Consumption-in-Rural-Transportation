<?php
session_start();
require_once __DIR__ . '/dbadminchat.php'; // Adjust the path as necessary

// Initialize database connection
$adminId = $_SESSION['admin_id']; // Assuming you have the admin ID in session
// Get the selected user ID from the query string
$userId = $_GET['user_id'] ?? null;
$messageHandler = new AdminMessage($adminId, $userId);

// Call the message submission method
$messageHandler->messageSubmission();

// Handle search input
$search = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $search = htmlspecialchars($_POST['search']);
}

// Fetch users based on search term
$users = $messageHandler->getUsers($search);

// Fetch messages for the selected user
$messages = $userId ? $messageHandler->getMessages($userId) : [];
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
        
        <!-- Sidebar -->
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
                        <?php foreach ($messages as $msg): ?>
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

        <form method="POST" class="form-container">
            <textarea name="adminMessage" class="input" placeholder="Type your message..." required></textarea>
            <button type="submit" class="button">Send</button>
        </form>
        </div>
    </div>
</body>
</html>