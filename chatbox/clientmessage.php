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
    <style>

        #sidebar {
            width: 330px; /* Fixed width for sidebar */
            height: 400px; /* Set height to 400px */
            background-color: #f5f5f5; /* Light background for sidebar */
            padding: 15px;
            transition: transform 0.3s ease;
            border-radius: 15px;
            position: fixed;
            right: 0; /* Align to the right */
            bottom: 0; /* Align to the bottom */
            transform: translateX(100%); /* Initially hide the sidebar */
            overflow-y: auto; /* Allow scrolling for long message lists */
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1); /* Optional shadow for sidebar */
        }

        #sidebar.active {
            transform: translateX(0); /* Show the sidebar */
        }

        #toggle-sidebar {
            position: fixed; /* Fixed position to stay in the viewport */
            bottom: 20px; /* Distance from the bottom */
            right: 20px; /* Distance from the right */
            cursor: pointer;
            z-index: 2; /* Above the sidebar */
            background-color: #D5DFF2; /* Button color */
            color: white; /* Button text color */
            border: none; /* No border */
            border-radius: 50%; /* Circular button */
            width: 50px; /* Button width */
            height: 50px; /* Button height */
            display: flex; /* Flexbox for centering */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            font-size: 24px; /* Icon size */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Shadow effect */
        }

        #toggle-sidebar:hover {
            background-color: #4F5576; /* Darker blue on hover */
        }

        #messages {
            flex: 1; /* Take up available space */
            overflow-y: auto; /* Enable scrolling for overflow */
            padding: 20px;
            background-color: #f9f9f9; /* Slightly lighter gray for the message area */
            display: flex; /* Flex layout */
            flex-direction: column; /* Stack messages vertically */
            gap: 10px; /* Spacing between messages */
            height: calc(100% - 160px); /* Adjust height to account for the form */
        }

        .message {
            padding: 10px 15px;
            border-radius: 10px;
            max-width: 70%; /* Limit message width */
            font-size: 14px;
            line-height: 1.5;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow for messages */
            word-wrap: break-word; /* Prevent long words from breaking layout */
        }

        .client-message {
            background-color: #0088cc; /* Telegram blue */
            color: #ffffff; /* White text for contrast */
            align-self: flex-end; /* Align client messages to the right */
            border-top-right-radius: 0; /* Square top-right corner */
            margin: 0 10px 10px auto; /* Consistent margin */
        }

        .admin-message {
            background-color: #e5e5ea; /* Light gray for admin messages */
            color: #333333; /* Darker text for contrast */
            align-self: flex-start; /* Align admin messages to the left */
            border-top-left-radius: 0; /* Square top-left corner */
            margin: 0 auto 10px 10px; /* Consistent margin */
        }

        .form-container {
            display: flex; /* Flexbox for form layout */
            padding: 15px;
            background-color: #f3f3f3; /* Match the page background */
            border-top: 1px solid #ccc; /* Subtle border for separation */
            position: fixed; /* Fixed position to stay at the bottom */
            bottom: 0; /* Align to the bottom */
            left: 0; /* Align to the left */
            right: 0; /* Align to the right */
            z-index: 1; /* Ensure it appears above other elements */
        }

        .input {
            flex: 1; /* Take up available space */
            padding: 10px;
            border: 1px solid #ccc; /* Border around input */
            border-radius: 8px; /* Rounded corners */
            font-size: 14px;
            outline: none;
        }

        .input:focus {
            border-color: #0088cc; /* Highlight on focus */
            box-shadow: 0 0 4px rgba(0, 136, 204, 0.5); /* Subtle focus effect */
        }

        .button {
            background-color: #0088cc; /* Telegram blue */
            color: white; /* White text */
            border: none;
            border-radius: 8px; /* Rounded corners */
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Smooth hover transition */
        }

        .button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .button:active {
            background-color: #004b99; /* Even darker blue on click */
        }

        .admin-header {
            background-color: #D5DFF2; /* Blue background for header */
            color: white; /* White text */
            padding: 10px;
            border-radius: 10px 10px 0 0; /* Rounded top corners */
            text-align: center; /* Centered text */
            font-size: 18px; /* Larger font size */
            margin-bottom: 10px; /* Space below header */
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <div class="admin-header">Admin Messages</div>
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