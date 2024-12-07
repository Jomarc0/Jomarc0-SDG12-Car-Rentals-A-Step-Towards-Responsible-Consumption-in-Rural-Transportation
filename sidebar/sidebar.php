<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
    <link rel="stylesheet" href="../css/sidebar.css">
</head>
<body>
    <button class="toggle-sidebar" id="toggleButton" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>

    <div class="sidebar-container" id="sidebar-container">
        <div class="sidebar" id="sidebar">
            <ul>
                <li><a href="profile.php" class="menu-item">Profile</a></li>
                <li><a href="rent.php" class="menu-item">Rent</a></li>
                <li><a href="renthistory.php" class="menu-item">Rent History</a></li>
                <li><a href="../ResetPassword/forgetpassword.php" class="menu-item">Change Password</a></li>
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
        toggleButton.innerHTML = '<i class="fa-solid fa-bars"></i>'; // Change button text
    } else {
        sidebarContainer.classList.add('show-sidebar'); // Show the sidebar
        toggleButton.innerHTML = '<i class="fa-solid fa-bars"></i>'; // Change button text
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
        toggleButton.innerHTML = '<i class="fa-solid fa-bars"></i>'; // Change button text
    }
});
    </script>
</body>
</html>