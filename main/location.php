<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Area</title>
    <link rel="stylesheet" href="../css/location.css"> 
    <link rel="stylesheet" href="../css/header.css"> 
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
</body>
</html>