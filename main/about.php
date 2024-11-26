<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Form</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #F7F9FC; /* Light background for the body */
    color: #333333; /* Dark text color for better readability */
}

.content-container {
    max-width: 1300px; /* Limit the width for better readability */
    margin: auto; /* Center the container */
    background-color: #9FA7BF; /* White background for contrast */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Deeper shadow for depth */
    padding: 20px; /* Padding inside the container */
    transition: box-shadow 0.3s; /* Smooth transition for shadow effect */
}

.content-container:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2); /* Shadow effect on hover */
}

.content-container h1, h2 {
    color: #2C3E50; /* Darker shade for headings */
    border-bottom: 2px solid #2C3E50; /* Underline for headings */
    padding-bottom: 10px; /* Space below headings */
}

p {
    line-height: 1.6; /* Improve readability */
    margin: 15px 0;
}

/* Button styles */
.btn {
    display: inline-block;
    background-color: #2C3E50; /* Button background color */
    color: white; /* Button text color */
    padding: 10px 20px; /* Button padding */
    border: none; /* Remove border */
    border-radius: 5px; /* Rounded corners for buttons */
    text-align: center; /* Center text */
    text-decoration: none; /* Remove underline */
    margin: 10px 0; /* Margin around buttons */
    transition: background-color 0.3s, transform 0.3s; /* Smooth transition for hover effect */
}

.btn:hover {
    background-color: #1A252F; /* Darker shade on hover */
    transform: translateY(-2px); /* Lift effect on hover */
}

/* Separate div for the button */
.button-container {
    text-align: center; /* Center the button */
    margin-top: 20px; /* Space above the button */
}

/* Info section styles */
.info-section {
    display: flex; /* Use flexbox for layout */
    justify-content: space-between; /* Space out the containers */
    flex-wrap: wrap; /* Allow wrapping on smaller screens */
    margin-top: 30px; /* Space above the info section */
}

.info-container {
    flex: 1; /* Allow containers to grow equally */
    min-width: 250px; /* Minimum width for each container */
    margin: 10px; /* Margin around each container */
    padding: 15px; /* Padding inside each container */
    background-color: #D5DFF2; /* Light background for info containers */
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition for hover */
}

.info-container:hover {
    transform: scale(1.03); /* Slightly enlarge on hover */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Shadow effect on hover */
}

.info-title {
    font-weight: bold; /* Bold title */
    margin-bottom: 10px; /* Space below title */
}

/* Responsive design */
@media (max-width: 600px) {
    body {
        padding: 10px; /* Reduce padding on smaller screens */
    }

    .btn {
        width: 100%; /* Full-width buttons on small screens */
        box-sizing: border-box; /* Include padding in width calculation */
    }

    .info-section {
        flex-direction: column; /* Stack info containers on small screens */
    }
}
    </style>
</head>
<body>
    <?php include('../header/header.php');?>

    <div class="content-container">
        <h1>Welcome to QuickWheels!</h1>
        <p>At QuickWheels, we understand that mobility is key to your freedom and convenience. Whether you're traveling for business, planning a vacation, or need a temporary vehicle, we offer a wide range of rental cars to suit your needs. Our mission is to provide exceptional service, competitive pricing, and a hassle-free rental experience.</p>

        <img src="car-rental.jpg" alt="Car Rental" style=" width: 100%; height: 200px; object-fit: cover; border-radius: 10px; margin-bottom: 20px;">

        <h2>Why Choose QuickWheels?</h2>
        <div class="info-section">
            <div class="info-container">
                <div class="info-title">Diverse Fleet</div>
                <p>We offer a diverse selection of vehicles, from compact cars for city driving to spacious SUVs for family trips. Whether you need a fuel-efficient car for a quick errand or a luxury vehicle for a special occasion, we have something for everyone.</p>
                    
            </div>
            <div class="info-container">
                <div class="info-title">Affordable Rates</div>
                <p>QuickWheels is committed to providing competitive pricing without compromising on quality. Our transparent pricing model ensures that you know exactly what you're paying for, with no hidden fees.</p>
                <img src="affordable-rates.jpg" alt="Affordable Rates" style="width: 100%; border-radius: 8px;">
            </div>
            <div class="info-container">
                <div class="info-title">Convenient Booking</div>
                <p>Our user-friendly online booking system allows you to reserve your vehicle in just a few clicks. Choose your desired car, pick-up location, rental dates, and enjoy a seamless booking experience.</p>
                <img src="convenient-booking.jpg" alt="Convenient Booking" style="width: 100%; border-radius: 8px;">
            </div>
            <div class="info-container">
                <div class="info-title">Flexible Rental Options</div>
                <p>We offer flexible rental terms, including daily, weekly, and monthly rentals. Whether you need a car for a day or an extended period, QuickWheels has you covered.</p>
                <img src="flexible-options.jpg" alt="Flexible Rental Options" style="width: 100%; border-radius: 8px;">
            </div>
            <div class="info-container">
                <div class="info-title">24/7 Customer Support</div>
                <p>Our dedicated customer service team is available around the clock to assist you with any questions or concerns. We're here to ensure that your rental experience is smooth and enjoyable.</p>
                <img src="customer-support.jpg" alt="24/7 Customer Support" style="width: 100%; border-radius: 8px;">
            </div>
            <div class="info-container">
                <div class="info-title">Easy Returns</div>
                <p>Returning your vehicle is quick and easy. Simply drop off your car at the designated location, and we'll handle the rest.</p>
                <img src="easy-returns.jpg" alt="Easy Returns" style="width: 100%; border-radius: 8px;">
            </div>
        </div>

        <h2>How It Works</h2>
        <div class="info-section">
            <div class="info-container">
                <div class="info-title">Select Your Vehicle</div>
                <p>Browse our online fleet and choose the car that best fits your needs.</p>
                <img src="select-vehicle.jpg" alt="Select Your Vehicle" style="width: 100%; border-radius: 8px;">
            </div>
            <div class="info-container">
                <div class="info-title">Make a Reservation</div>
                <p>Fill out our simple reservation form with your details, including pick-up and drop-off locations, rental dates, and any additional options you may need.</p>
                <img src="make-reservation.jpg" alt="Make a Reservation" style="width: 100%; border-radius: 8px;">
            </div>
            <div class="info-container">
                <div class="info-title">Pick Up Your Car</div>
                <p>Visit your chosen location to pick up your vehicle. Our friendly staff will assist you with the process and answer any questions.</p>
                <img src="pick-up-car.jpg" alt="Pick Up Your Car" style="width: 100%; border-radius: 8px;">
            </div>
            <div class="info-container">
                <div class="info-title">Enjoy Your Journey</div>
                <p>Hit the road and enjoy your rental experience with QuickWheels. Explore your destination at your own pace!</p>
                <img src="enjoy-journey.jpg" alt="Enjoy Your Journey" style="width: 100%; border-radius: 8px;">
            </div>
             <div class="info-container">
                <div class="info-title">Return the Vehicle</div>
                <p>Return your vehicle at the agreed-upon time and location. We’ll handle the rest!</p>
                <img src="return-vehicle.jpg" alt="Return the Vehicle" style="width: 100%; border-radius: 8px;">
            </div>
        </div>
        
        <div class="button-container">
            <a href="reservation.php" class="btn">Make a Reservation</a> <!-- Button to make a reservation -->
        </div>
    </div>
</body>
</html>