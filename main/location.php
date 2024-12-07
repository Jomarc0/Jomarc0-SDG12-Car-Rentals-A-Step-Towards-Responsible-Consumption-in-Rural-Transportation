<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Area</title>
    <link rel="stylesheet" href="../css/locations.css">
</head>
<body>
    <?php include('../header/header.php'); ?>
    <div class="container">
        <?php
        // Define locations in CALABARZON
        $locations = [
            [
                'name' => 'Cavite',
                'address' => 'Cavite, Philippines',
                'map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62118.38484812431!2d120.82926956561016!3d14.47957047765805!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33b9d7f4c7d2ed8d%3A0x11ee4690c170db21!2sCavite%2C%20Philippines!5e0!3m2!1sen!2sus!4v1631679993497!5m2!1sen!2sus'
            ],
            [
                'name' => 'Laguna',
                'address' => 'Laguna, Philippines',
                'map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.7831693817463!2d121.23372981519425!3d14.31355968810968!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bdfe8e5cb5d4c7%3A0x238ee3b3b66c6790!2sLaguna%2C%20Philippines!5e0!3m2!1sen!2sus!4v1631678311993!5m2!1sen!2sus'
            ],
            [
                'name' => 'Batangas City',
                'address' => 'Batangas City, Batangas, Philippines',
                'map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.2946581882923!2d121.05619821519437!33d13.756565688131847!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33b8f0e9f0e9f0e9%3A0x4e4e4e4e4e4e4e4e!2sBatangas%20City%2C%20Batangas%2C%20Philippines!5e0!3m2!1sen!2sus!4v1631678311993!5m2!1sen!2sus'
            ],
            [
                'name' => 'Rizal',
                'address' => 'Rizal Province, Philippines',
                'map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62214.85060243222!2d121.18421708373265!3d14.599512876001725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33b8a7f120711fbd%3A0x6a8cb29037e0a6f5!2sRizal%2C%20Philippines!5e0!3m2!1sen!2sus!4v1631680104095!5m2!1sen!2sus'
            ],
            [
                'name' => 'Quezon',
                'address' => 'Quezon Province, Philippines',
                'map' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62210.493548283285!2d121.78713781600508!3d14.164943174664744!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33b6d09501352b8b%3A0x58023d79fb2563d2!2sQuezon%2C%20Philippines!5e0!3m2!1sen!2sus!4v1631680059184!5m2!1sen!2sus'
            ]
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