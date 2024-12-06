<?php
require_once __DIR__ . '/../dbcon/dbcon.php';
require_once 'RentUpdate.php'; 

$deleteRent = new RentUpdate(); //class deleterent form deleterent.php

if (isset($_POST['delete-btn'])) { //check if form is submitted
    $rent_id = $_POST['rent_id']; //get the rent id from the hidden rent id
    
    $message = $deleteRent->deleteRental($rent_id); //call the delete function
    echo $message; 
}
?>