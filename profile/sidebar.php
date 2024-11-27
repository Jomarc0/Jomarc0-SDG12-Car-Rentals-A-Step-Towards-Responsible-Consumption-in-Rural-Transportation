<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #D5DFF2; /* Light background */
}

.toggle-sidebar {
    position: absolute;
    top: 140px;
    left: 0;
    padding: 10px 15px;
    background-color: #4F5576; /* Dark blue-green color */
    color: #D5DFF2; /* Light text color */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.toggle-sidebar:hover {
    background-color: #3e8e41; /* Darker blue-green color on hover */
}

.sidebar-container {
    position: fixed;
    left: -250px; /* Hide the sidebar by default */
    top: 0;
    height: 100%;
    width: 250px;
    background-color: #181b26; /* Dark background */
    margin-top: 140px;
    overflow-x: hidden;
    transition: 0.3s;
    z-index: 1000;
}

.sidebar {
    padding: 20px;
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar li {
    margin: 15px 0;
}

.menu-item {
    text-decoration: none;
    color: #D5DFF2; /* Light text color */
    padding: 10px;
    display: block;
    transition: background-color 0.3s;
}

.menu-item:hover {
    background-color: #9FA7BF; /* Light gray-blue color on hover */
}

/* Add this to show the sidebar when the button is clicked */
.show-sidebar {
    left: 0; /* Show the sidebar */
}
</style>
</head>
<body>
    <button class="toggle-sidebar" id="toggleButton" onclick="toggleSidebar()">Show Sidebar</button>

    <div class="sidebar-container" id="sidebar-container">
        <div class="sidebar" id="sidebar">
            <ul>
                <li><a href="profile.php" class="menu-item">Profile</a></li>
                <li><a href="rent.php" class="menu-item">Rent</a></li>
                <li><a href="renthistory.php" class="menu-item">Rent History</a></li>
                <li><a href="../ResetPassword/forgetpassword.php" class="menu-item">Change Password</a></li>
                <li><a href="delete_account.html" class="menu-item">Delete Account</a></li>
            </ul>
        </div>
    </div>
    <script>
        function toggleSidebar() {
    const sidebarContainer = document.getElementById('sidebar-container');
    const toggleButton = document.getElementById('toggleButton');

    // Toggle the sidebar visibility
    if (sidebarContainer.classList.contains('show-sidebar')) {
        sidebarContainer.classList.remove('show-sidebar'); // Hide the sidebar
        toggleButton.textContent = 'Show Sidebar'; // Change button text
    } else {
        sidebarContainer.classList.add('show-sidebar'); // Show the sidebar
        toggleButton.textContent = 'Hide Sidebar'; // Change button text
    }
}

// Hide sidebar by default on page load
window.onload = function() {
    const sidebarContainer = document.getElementById('sidebar-container');
    sidebarContainer.classList.remove('show-sidebar'); // Ensure the sidebar is hidden initially
};

// Close sidebar when clicking outside of it
window.addEventListener('click', function(event) {
    const sidebarContainer = document.getElementById('sidebar-container');
    const toggleButton = document.getElementById('toggleButton');

    // Check if the click is outside the sidebar and the toggle button
    if (!sidebarContainer.contains(event.target) && !toggleButton.contains(event.target)) {
        sidebarContainer.classList.remove('show-sidebar'); // Hide the sidebar
        toggleButton.textContent = 'Show Sidebar'; // Reset button text
    }
});
    </script>
</body>
</html>