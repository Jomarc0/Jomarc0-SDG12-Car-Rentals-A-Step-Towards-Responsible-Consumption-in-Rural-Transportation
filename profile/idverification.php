<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; 
require_once 'dbverifyid.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $country = $_POST['country'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $id_number = $_POST['id_number'];
    $id_photo = $_FILES['id_photo'];

    // Create an instance of the IdentityVerification class
    $identityVerification = new IdentityVerification();

    // Call the verifyIdentity method to process the data
    $result = $identityVerification->verifyIdentity($user_id, $country, $id_number, $id_photo, $first_name, $last_name, $dob, $address);

    // Redirect or display the result
    if (strpos($result, 'successful') !== false) {
        echo "<script>alert('$result'); window.location.href='success_page.php';</script>";
    } else {
        echo "<script>alert('$result'); window.history.back();</script>";
    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Identity Verification Form</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
        color: #333;
      }
      
      .form-container {
        max-width: 800px;
        margin: 50px auto;
        background: #fff;
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }
      
      h1, h2 {
        text-align: center;
        margin-bottom: 20px;
      }
      
      fieldset {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
      }
      
      legend {
        padding: 0 10px;
        font-weight: bold;
        font-size: 1.2em;
      }
      
      .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
      }
      
      label {
        font-size: 0.9em;
        margin-bottom: 5px;
      }
      
      input, select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1em;
      }
      
      input[type="file"] {
        display: none;
      }
      
      .upload-box {
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #ccc;
        padding: 20px;
        margin: 5px 0;
        border-radius: 5px;
        text-align: center;
        cursor: pointer;
        font-size: 0.9em;
      }
      
      .upload-box:hover {
        border-color: #007bff;
        background: #f0f8ff;
      }
      
      .upload-group {
        display: flex;
        gap: 15px;
      }
      
      .upload-group .upload-box {
        flex: 1;
      }
      
      button {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
      }
      
      button:hover {
        background-color: #0056b3;
      }
      
  </style>
</head>
<body>
<div class="header">
        <?php include('../header/header.php'); ?>  <!-- Including header -->
    </div>
    <?php include('sidebar.php');?>  <!--including my sidebar -->
  <div class="form-container">
    <h1>Identity Verification</h1>
    <h2>Personal Verification</h2>

    <form class="verification-form" action="" method="POST" enctype="multipart/form-data">
      <!-- Basic Info Section -->
      <fieldset>
        <legend>Basic Info</legend>
        <div class="form-group">
          <label for="country">Country / Region</label>
          <select id="country" name="country" required>
            <option value="Philippines" selected>Philippines</option>
          </select>
        </div>
        <div class="form-group">
          <label for="first-name">First Name</label>
          <input type="text" id="first-name" name="first_name" placeholder="First Name" required>
        </div>
        <div class="form-group">
          <label for="last-name">Last Name</label>
          <input type="text" id="last-name" name="last_name" placeholder="Last Name" required>
        </div>
        <div class="form-group">
          <label for="dob">Date of Birth</label>
          <input type="date" id="dob" name="dob" placeholder="Date of Birth" required>
        </div>
        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" name="address" id="address" placeholder="House No/Street/Brgy/City" required>
        </div>
      </fieldset>
      <!-- Document Upload Section -->
      <fieldset>
        <legend>Upload Documents</legend>
        <div class="form-group">
          <label for="certificate-type">Please select the certificate type for submission</label>
          <select id="certificate-type" name="certificate_type" required>
            <option value="id-card" selected>Driver's License</option>
            <!-- Add more options as needed -->
          </select>
        </div>
        <div class="form-group">
          <label for="id-number">Number</label>
          <input type="text" id="id-number" name="id_number" placeholder="Enter the ID number" required>
        </div>
        <div class="form-group">
          <label>Upload pics of ID card</label>
          <div class="upload-group">
            <label class="upload-box">
              <input type="file" name="id_photo" accept=".jpg, .jpeg, .png" required>
              <span>Click to upload the obverse of ID photo</span>
            </label>
          </div>
        </div>
      </fieldset>
      <button type="submit">Submit Verification</button>
    </form>
  </div>
</body>
</html>