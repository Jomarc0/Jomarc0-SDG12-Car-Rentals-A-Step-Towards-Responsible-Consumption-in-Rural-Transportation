<?php
session_start();

require_once '../Rents/Rent.php'; 

$carRental = new Rental(); //call the rental class 
$cars = $carRental->getRentalHistory(); //call the functoin getter 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rented History</title>
    <link rel="stylesheet" href="../css/vehicle.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/rent.css">
</head>
<body>
    <div class="header">
        <?php include('../header/header.php'); ?>  <!--include the header -->
    </div>
    <?php include('../sidebar/sidebar.php'); ?> <!--include the side bar -->
    <div class="container">
        <div class="main-content" id="main-content">
            <div class="car-container">
                <?php foreach ($cars as $car): ?>
                    <div class="car-card">
                        <form action="../main/delete.php" method="post"> <!-- this is for the cancel button -->
                            <input type="hidden" name="rent_id" value="<?php echo $car['rent_id']; ?>"> <!-- hidden the rent id -->
                            <button type="submit" class="delete-button" name="delete-btn">X</button>
                        </form>
                        <?php
                         // display the car image
                        $imagePath = $carRental->getVehicleImage($car['vehicle_type']);
                        ?>
                        <!--diplay the image -->
                        <img class="car-image" src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($car['vehicle_type']); ?>">
                        <div class="car-info">
                            <!--display the rent car infomation -->
                            <h2 class="car-title"><?php echo htmlspecialchars($car['vehicle_type']); ?></h2>
                            <span>Booking Area: <?php echo htmlspecialchars($car['booking_area']); ?></span><br>
                            <span>Destination: <?php echo htmlspecialchars($car['destination']); ?></span><br>
                            <span>Trip Date & Time: <?php echo htmlspecialchars($car['trip_date_time']); ?></span><br>
                            <span>Return Date & Time: <?php echo htmlspecialchars($car['return_date_time']); ?></span><br>
                            <span>Rent Status: <?php echo htmlspecialchars($car['rent_status']); ?></span><br>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="../js/vehicle.js"></script>
</body>
</html>