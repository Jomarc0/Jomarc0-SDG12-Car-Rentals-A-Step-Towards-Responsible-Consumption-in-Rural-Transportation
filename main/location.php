<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Area</title>
    <!-- <link rel="stylesheet" href="../css/location.css">  -->
     <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #D5DFF2; /* Light background for the body */
}

.container {
    max-width: 1000px; /* Set a maximum width for the container */
    margin: 50px auto; /* Center the container */
    padding: 30px; /* Add padding around the container */
    display: flex; /* Use Flexbox for layout */
    flex-wrap: wrap; /* Allow items to wrap onto the next line */
    justify-content: space-between; /* Space out items evenly */
    gap: 20px; /* Increased gap for better spacing */
}

.map {
    width: 100%; /* Make the map responsive */
    height: 200px; /* Set a fixed height */
    border: 0; /* Remove border */
    border-radius: 10px; /* Rounded corners for the map */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

.location {
    background-color: white; /* White background for location cards */
    border-radius: 10px; /* Rounded corners for location cards */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    padding: 20px; /* Padding inside location cards */
    flex: 1 1 calc(30% - 20px); /* Responsive width with margin */
    min-width: 250px; /* Minimum width for location cards */
    transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition for hover effects */
}

.location:hover {
    transform: translateY(-5px); /* Lift effect on hover */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Darker shadow on hover */
}

a {
    text-decoration: none; /* Remove underline from all links */
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically (if needed) */
    margin-top: 10px;
}

.request-quote {
    background-color: #4F5576; /* Main button color */
    text-decoration: none; /* Remove underline */
    color: white; /* Text color */
    border: none; /* Remove border */
    padding: 10px 25px; /* Padding for button */
    font-size: 1.2em; /* Font size */
    cursor: pointer; /* Pointer cursor on hover */
    border-radius: 5px; /* Rounded corners */
    transition: background-color 0.3s, transform 0.2s; /* Smooth transition for hover effects */
}

.request-quote:hover {
    background-color: #181b26; /* Darker shade on hover */
    transform: translateY(-2px); /* Lift effect on hover */
}
     </style>
</head>
<body>
    <?php include('../header/header.php'); ?>
    <div class="container">
        <?php
        // Define locations in CALABARZON
        $locations = [
            [
                'name' => 'Tagaytay City',
                'address' => 'Tagaytay City, Cavite, Philippines',
                'map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.2946581882923!2d121.55619821519437!3d14.090565688131847!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33b8f0e9f0e9f0e9%3A0x4e4e4e4e4e4e4e4e!2sTagaytay%20City%2C%20Cavite%2C%20Philippines!5e0!3m2!1sen!2sus!4v1631678311993!5m2!1sen!2sus'
            ],
            [
                'name' => 'Batangas City',
                'address' => 'Batangas City, Batangas, Philippines',
                'map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.2946581882923!2d121.05619821519437!3d13.756565688131847!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33b8f0e9f0e9f0e9%3A0x4e4e4e4e4e4e4e4e!2sBatangas%20City%2C%20Batangas%2C%20Philippines!5e0!3m2!1sen!2sus!4v1631678311993!5m2!1sen!2sus'
            ],
            [
                'name' => 'Laguna',
                'address' => 'Laguna, Philippines',
                'map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.7831693817463!2d121.23372981519425!3d14.31355968810968!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bdfe8e5cb5d4c7%3A0x238ee3b3b66c6790!2sLaguna%2C%20Philippines!5e0!3m2!1sen!2sus!4v1631678311993!5m2!1sen!2sus'
            ],
            [
                'name' => 'Cabuyao, Laguna',
                'address' => 'Cabuyao, Laguna, Philippines',
                'map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.594211194615!2d121.08556351519424!3d14.26952658813818!2m3!1f0!2f0!3f 0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd7b0b4b8a330d%3A0x256ae90d2f35c8f5!2sCabuyao%2C%20Laguna%2C%20Philippines!5e0!3m2!1sen!2sus!4v1631678325473!5m2!1sen!2sus'
            ],
            [
                'name' => 'Lucena City',
                'address' => 'Lucena City, Quezon, Philippines',
                'map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3874.123447930378!2d121.62225401519417!3d13.941928688120337!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33b6a8f2b8dff5c9%3A0x527bbd2d2150b02!2sLucena%20City%2C%20Quezon%2C%20Philippines!5e0!3m2!1sen!2sus!4v1631678347956!5m2!1sen!2sus'
            ]
            // Add more locations in CALABARZON here
        ];

        // Loop through locations and display each one
        foreach ($locations as $location) {
            ?>
            <div class="location">
                <h2><?php echo $location['name']; ?></h2>
                <p><?php echo $location['address']; ?></p>
                <iframe class="map" src="<?php echo $location['map']; ?>" allowfullscreen="" loading="lazy" style="border:0;" allowfullscreen></iframe>
                <a href="reservation.php" class="request-quote">Request Quote</a>
            </div>
            <?php
        }
        ?>
    </div>
    <?php require ('../chatbox/clientmessage.php'); //include the chat ?> 
</body>
</html>