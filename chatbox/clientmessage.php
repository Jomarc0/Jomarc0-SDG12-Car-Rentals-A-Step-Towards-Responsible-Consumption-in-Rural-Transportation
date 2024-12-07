<?php
require_once 'Message.php'; 
session_start(); 

$_SESSION['user'] = 'client'; // Sset client to log
$user_id = $_SESSION['user_id']; //session user-id

$messageHandler = new Message(null, $user_id); // admin ass null

$messageHandler->userMessage(); //this insert the message of the user

$messages = $messageHandler->getMessages($user_id); //get all the message 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Chat</title>
    <link rel="stylesheet" href="../css/clientmessage.css">
</head>
<body>
    <div id="sidebar">
        <div class="admin-header">Admin Messages</div>
        <div id="messages">
        <?php
        // Display messages
            foreach ($messages as $msg) { //loop the message of the user and client
                $isAdmin = isset($_SESSION['user']) && $_SESSION['user'] === 'admin';
                $class = ($msg['sender'] === 'admin' && !$isAdmin) || ($msg['sender'] === 'client' && $isAdmin) ? 'other-message' : ($msg['sender'] === 'admin' ? 'admin-message' : 'client-message');
                echo "<div class='message $class'>{$msg['sender']}: {$msg['message']}</div>";
            }
        ?>
        </div>
        <form method="post">
            <div class="form-container">
                <div class="control is-expanded"> <!--this is the input box -->
                    <input class="input" type="text" name="client_message" placeholder="Type your message..." required>
                </div>
                <div class="control">
                    <button class="button is-info" type="submit">Send</button>
                </div>
            </div>
        </form>
    </div>

    <div id="toggle-sidebar"><i class="fa-solid fa-message"></i></div> <!-- to make this in the main page -->

    <script>
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>