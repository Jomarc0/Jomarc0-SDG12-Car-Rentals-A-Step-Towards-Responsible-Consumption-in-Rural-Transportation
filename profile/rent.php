<?php
session_start();

require_once 'dbrent.php'; 

$rentedCars = new RentedCars(); //call the rented car class 
$cars = $rentedCars->getCars(); //use the function getter
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rented Cars</title>
    <link rel="stylesheet" href="../css/vehicle.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/rent.css">
</head>
<body>
    <header class="header">
        <?php include('../header/header.php'); ?>  
    </header>
    <?php include('sidebar.php'); ?>
    <div class="container">
        <div class="main-content" id="main-content">
            <div class="car-container">
                <?php foreach ($cars as $car): ?>
                    <div class=" car-card">
                        <form action="../main/cancel.php" method="post"> <!-- this is for the cancel button -->
                            <input type="hidden" name="rent_id" value="<?php echo $car['rent_id']; ?>"> <!-- hidden the rent id -->
                            <button type="submit" class="cancel-button" name="cancel-btn">X</button>
                        </form>
                        <?php
                        // display the car image
                        $imagePath = $rentedCars->getVehicleImage($car['vehicle_type']);
                        // calculate the remaining time 
                        $returnDateTime = new DateTime($car['return_date_time']);
                        $currentDateTime = new DateTime();
                        $interval = $currentDateTime->diff($returnDateTime);
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
                            <span>Remaining Time: 
                                <?php
                                // display the remainin time
                                if ($returnDateTime > $currentDateTime) {
                                    echo $interval->format('%d days, %h hours, %i minutes'); //use this format to display the day hours and minutes
                                } else {
                                    echo "Returned";
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="../js/vehicle.js"></script>
</body>
</html>