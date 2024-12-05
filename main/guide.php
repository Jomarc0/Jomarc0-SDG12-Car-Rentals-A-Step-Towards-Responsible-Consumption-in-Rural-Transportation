<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Reservation Guide</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212; /* Dark background */
        }

        .container {
            width: 700px; 
            margin: 0 auto; 
            padding: 3rem; 
            background-color: #1c1c1c; /* Darker background for the container */
            border-radius: 8px; /* Rounded corners for the container */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Shadow for depth */
        }

        .container h1 {
            text-align: center;
            margin-bottom: 2rem; 
            color: #f7b531; /* Highlight color for the title */
            font-size: 40px;
        }

        .container p {
            text-align: center; 
            margin-bottom: 2rem; 
            color: #e0e0e0; /* Lighter color for the paragraph */
            font-size: 18px;
        }

        .reservation-guide {
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            grid-gap: 1rem; 
        }

        .step {
            background-color: #2c2c2c; /* Darker background for steps */
            padding: 2rem; 
            border-radius: 8px; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); 
            text-align: center; 
            transition: background-color 0.3s; 
        }

        .step:hover {
            background-color: #3a3a3a; /* Change background color on hover */
        }

        .step h3 {
            color: #f7b531; /* Highlight color for step titles
                        margin-bottom: 1rem; 
        }

        .step p {
            color: #e0e0e0; /* Lighter color for step descriptions */
            line-height: 1.6; 
        }

        .step .icon {
            font-size: 2rem; 
            color: #f7b531; /* Use highlight color for icons */
            margin-bottom: 1rem; 
        }
    </style>
</head>
<body>
    <?php include('../header/header.php'); ?>
    <div class="container">
        <h1>How To Book Car Rental in QuickWheels</h1>
        <p>Great! You've decided to finally take your first steps to rent a car. Here are some important reminders to make the car rental process convenient for you. If you have any questions, do let us know, we're here to help!</p>

        <div class="reservation-guide">
            <div class="step">
                <h3>1</h3>
                <div class="icon">
                    <i class="fas fa-file-alt"></i> 
                </div>
                <h3>Booking Form</h3>
                <p>Fill up the Request Quotation Form.</p>
            </div>

            <div class="step">
                <h3>2</h3>
                <div class="icon">
                    <i class="fas fa-envelope"></i> 
                </div>
                <h3>Wait for Quotation</h3>
                <p>Check your email for the quotation.</p>
            </div>
            <div class="step">
                <h3>3</h3>
                <div class="icon">
                    <i class="fas fa-credit-card"></i> 
                </div>
                <h3>Pay the Rental Fee</h3>
                <p>You will need to make the required full payment to confirm your booking.</p>
            </div>

            <div class="step">
                <h3>4</h3>
                <div class="icon">
                    <i class="fas fa-check-circle"></i> 
                </div>
                <h3>Booking Approval</h3>
                <p>Once your booking is approved, we will match your booking request with a vehicle.</p>
            </div>

            <div class="step">
                <h3>5</h3>
                <div class="icon">
                    <i class="fas fa-envelope-open-text"></i> 
                </div>
                <h3>Booking Confirmation</h3>
                <p>Expect an email with the booking confirmation.</p>
            </div>
        </div>
    </div>
    <?php require ('../chatbox/clientmessage.php'); //include the chat ?> 
</body>
</html>