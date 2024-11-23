<?php
require_once 'dbclient.php'; // Adjust the path as necessary

// Ensure that the session variable for the user is set to 'client'
$_SESSION['user'] = 'client'; // This line simulates that you are logged in as a client
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

$messageHandler = new UserMessage($user_id); //call messagehandler class
$messageHandler->messageSubmission(); 
$messages = $messageHandler->getMessages();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Chat</title>
    <link rel="stylesheet" href="../css/clientchat.css">
</head>
<body>
    <div id="sidebar">
        <h2>Messages</h2>
        <div id="messages">
        <?php
        // Display messages
            foreach ($messages as $msg) {
                $isAdmin = isset($_SESSION['user']) && $_SESSION['user'] === 'admin';
                $class = ($msg['sender'] === 'admin' && !$isAdmin) || ($msg['sender'] === 'client' && $isAdmin) ? 'other-message' : ($msg['sender'] === 'admin' ? 'admin-message' : 'client-message');
                echo "<div class='message $class'>{$msg['sender']}: {$msg['message']}</div>";
            }
        ?>
        </div>
        <form method="post">
            <div class="form-container">
                <div class="control is-expanded">
                    <input class="input" type="text" name="client_message" placeholder="Type your message..." required>
                </div>
                <div class="control">
                    <button class="button is-info" type="submit">Send</button>
                </div>
            </div>
        </form>
    </div>

    <div id="toggle-sidebar"><i class="fa-solid fa-message"></i></div>

    <script>
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>