<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About QuickWheels - Car Rental</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #F7F9FC; /* Light background for the body */
            color: #333333; /* Dark text color for better readability */
        }

        .content-container {
            max-width: 800px; /* Limit the width for better readability */
            margin: auto; /* Center the container */
            background-color: #FFFFFF; /* White background for contrast */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Deeper shadow for depth */
            padding: 30px; /* Increased padding inside the container */
            transition: box-shadow 0.3s; /* Smooth transition for shadow effect */
            display: flex; /* Flexbox for layout */
            flex-direction: column; /* Stack items vertically */
        }

        .content-container h1, h2 {
            color: #2C3E50; /* Darker shade for headings */
            border-bottom: 2px solid #2C3E50; /* Underline for headings */
            padding-bottom: 10px; /* Space below headings */
            margin-bottom: 20px; /* Space below headings */
        }

        p {
            line-height: 1.6; /* Improve readability */
            margin: 15px 0;
        }

        ul {
            list-style-type: disc; /* Bullet points */
            margin-left: 20px; /* Indent list */
            margin-bottom: 20px; /* Space below list */
        }

        /* Button styles */
        .btn {
            display: inline-block;
            background-color: #2C3E50; /* Button background color */
            color: white; /* Button text color */
            padding: 12px 25px; /* Button padding */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners for buttons */
            text-align: center; /* Center text */
            text-decoration: none; /* Remove underline */
            margin-top: 20px; /* Margin above button */
            transition: background-color 0.3s, transform 0.3s; /* Smooth transition for hover effect */
            font-size: 16px; /* Increase font size */
        }

        .btn:hover {
            background-color: #1A252F; /* Darker shade on hover */
            transform: translateY(-2px); /* Lift effect on hover */
        }

        /* Image styles */
        .image-container {
            flex: 1; /* Allow image to take available space */
            display: flex; /* Flexbox for layout */
            justify-content: center; /* Center the image */
            align-items: center; /* Center the image */
            margin-bottom: 20px; /* Space below image */
        }

        .image-container img {
            max-width: 100%; /* Responsive image */
            border-radius: 10px; /* Rounded corners for image */
        }

        /* Button container */
        .button-container {
            display: flex; /* Flexbox for layout */
            justify-content: flex-end; /* Align button to the right */
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .content-container {
                padding: 20px; /* Reduce padding on smaller screens */
            }

            .btn {
                width: 100%; /* Full-width buttons on small screens */
                box-sizing: border-box; /* Include padding in width calculation */
            }
        }
    </style>
</head>
<body>
    <?php include('../header/header.php');?>

    <div class="content-container">
        <div class="image-container">
            <img src="path/to/your/image.jpg" alt="QuickWheels Car Rental"> <!-- Placeholder for the image -->
        </div>
        <h1>About QuickWheels</h1>
        <p>Welcome to QuickWheels, your reliable partner for car rentals! We provide a diverse fleet of vehicles to cater to your travel needs, whether for business or leisure.</p>

        <h2>Why Choose Us?</h2>
        <p>At QuickWheels, we prioritize customer satisfaction by offering:</p>
        <ul>
            <li>Diverse selection of vehicles</ <li>Competitive pricing with no hidden fees</li>
            <li>User-friendly online booking system</li>
            <li>Flexible rental options</li>
            <li>24/7 customer support</li>
        </ul>

        <h2>Make a Reservation</h2>
        <p>Ready to hit the road? Click the button below to make your reservation.</p>
        <div class="button-container">
            <a href="reservation.php" class="btn">Make a Reservation</a> <!-- Button to make a reservation -->
        </div>
    </div>
</body>
</html>