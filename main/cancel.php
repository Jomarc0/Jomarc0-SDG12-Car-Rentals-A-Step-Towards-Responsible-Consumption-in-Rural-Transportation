<?php

require_once __DIR__ . '/../dbcon/dbcon.php'; 
require_once 'RentUpdate.php'; 

$rentalCancellation = new RentUpdate(); //class cancelrent from cancelrent.php

if (isset($_POST['cancel-btn'])) { //if form is submitted
    $rent_id = $_POST['rent_id']; //get the rent from the hidden rent
    
    $message = $rentalCancellation->cancelRental($rent_id);
    echo $message; 
}


?>