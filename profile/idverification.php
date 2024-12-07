<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; 
require_once 'UserProfile.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; // session the userid
    $country = $_POST['country'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $id_number = $_POST['id_number'];
    $id_photo = $_FILES['id_photo'];

    $verification = new UserAccount(); //call the useraccount class

    //put in the parameter the post data
    $result = $verification->verify($user_id, $country, $id_number, $id_photo, $first_name, $last_name, $dob, $address);
   
    if (strpos($result, 'successful') !== false) { // redirect to the same page of sucessful
      echo "<script>alert('$result'); window.location.href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "';</script>";
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
  <link rel="stylesheet" href="../css/idverification.css">
</head>
<body>
<div class="header">
        <?php include('../header/header.php'); ?>  <!-- including header -->
    </div>
    <?php include('../sidebar/sidebar.php');?>  <!--including my sidebar -->
  <div class="form-container">
    <h1>Identity Verification</h1>
    <h2>Personal Verification</h2>

    <form class="verification-form" action="" method="POST" enctype="multipart/form-data">
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

      <fieldset>
        <legend>Upload Documents</legend>
        <div class="form-group">
          <label for="certificate-type">Please select the certificate type for submission</label>
          <select id="certificate-type" name="certificate_type" required>
            <option value="id-card" selected>Driver's License</option>

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