<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Wheels</title>
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>

        <?php include('../header/header.php');?>

    <div class="container">
        <h1><span class="highlight">Quick Wheels</span></h1>
        <div class="right-corner">
            <p>Welcome to QuickWheels, your reliable partner for car rentals! We provide a diverse fleet of vehicles to cater to your travel needs, whether for business or leisure. <br>With a proven track record of service excellence, we cater to all your car rental needs in Manila and beyond.</p>
            <a href="reservation.php" class="btn-primary">Request Quote</a>
        </div>
    </div>

    <section class="services">
        <h2>Our Services</h2>
        <p>We understand that every event is unique, and we strive to accommodate your individual needs.</p>
        <div class="service-cards">
            <div class="card">
                <img src="../pictures/wedding.jpg" alt="Wedding Service">
                <h3>Wedding Service</h3>
            </div>
            <div class="card">
                <img src="../pictures/family.jpg" alt="Family Travel">
                <h3>Family Travel</h3>
            </div>
            <div class="card">
                <img src="../pictures/friends.webp" alt="Barkada Travel">
                <h3>Barkada Travel</h3>
            </div>
            <div class="card">
                <img src="../pictures/business.jpg" alt="Bussiness Travel">
                <h3>Bussiness Travel</ h3>
            </div>
        </div>
        <div class="features">
            <div class="feature">
                <i class="fa-solid fa-helmet-safety"></i>
                <h4>Safety First</h4>
                <p>Experienced staff and professionally trained chauffeurs.</p>
            </div>
            <div class="feature">
                <i class="fa-solid fa-money-bill"></i>
                <h4>Reasonable Rates</h4>
                <p>We offer you the right vehicle at the right price to fit your budget.</p>
            </div>
            <div class="feature">
                <i class="fa-solid fa-car"></i>
                <h4>Cars</h4>
                <p>An extensive Cars including sedans, SUV, van, puck up, sports car and tesla car.</p>
            </div>
            <div class="feature">
                <i class="fa-solid fa-map"></i>
                <h4>Calabarzon </h4>
                <p>We provide our transportation services in calabarzon.</p>
            </div>
        </div>
    </section>

    <section class="vehicle-container">
        <h2>Our Cars</h2>
        <div class="fleet-cards">
            <div class="car-container">
                <a href="vehicles.php" class="car-card">
                    <img class="car-image" src="../pictures/sedan.jpg" alt="Economy Sedan">
                    <div class="car-info">
                        <h2 class="car-title">Sedan</h2>
                        <span>Toyota Vios</span>
                        <i class="fa-solid fa-circle-info info-icon"></i>
                        <div class="info-popup">
                            <img class="car-image" src="../pictures/sedan.jpg" alt="Economy Sedan">
                            <p>Toyota Vios is a compact sedan that offers a comfortable ride and excellent fuel efficiency.</p>
                            <p>It features a 1.5L engine, automatic transmission, and a range of safety features.</p>
                        </div>
                        <div class="car-details">
                            <span>
                                <i class="fas fa-user"></i> 5
                            </span>
                            <span>
                                <i class="fas fa-car"></i> AT
                            </span>
                            <span>
                                <i class="fas fa-duotone fa-solid fa-gas-pump"></i> GASOLINE
                            </span>
                        </div>
                        <div class="price">Price: ₱ 1500/day <br>Price: ₱ 63/hour<</div> <!-- Price included -->
                    </div>
                </a>

                <a href="vehicles.php" class="car-card">
                    <img class="car-image" src="../pictures/VAN.jpg" alt="Van">
                    <div class="car-info">
                        <h2 class="car-title">Van</h2>
                        <span>Toyota Hiace</span>
                        <i class="fa-solid fa-circle-info info-icon"></i>
                        <div class="info-popup">
                            <img class="car-image" src="../pictures/VAN.jpg" alt="Van">
                            <p>Toyota Hiace is a spacious van perfect for family trips and group travel.</p>
                            <p>It features a comfortable interior, ample storage, and advanced safety features.</p>
                        </div>
                        <div class="car-details">
                            <span>
                                <i class="fas fa-user"></i> 15
                            </span>
                            <span>
                                <i class="fas fa-car"></i> AT
                            </span>
                            <span>
                                <i class="fas fa-gas-pump"></i> GASOLINE
                            </span>
                        </div>
                        <div class="price">Price: ₱ 4000/day <br>Price: ₱ 167/hour</div> <!-- Price included -->
                    </div>
                </a>

                <a href="vehicles.php" class="car-card">
                    <img class="car-image" src="../pictures/Full size suv.jpg" alt="Fullsize SUV">
                    <div class="car-info">
                        <h2 class="car-title">Fullsize SUV</h2>
                        <span>Toyota Fortuner</span>
                        <i class="fa-solid fa-circle-info info-icon"></i>
                        <div class="info-popup">
                            <img class="car-image" src="../pictures/Full size suv.jpg" alt="Fullsize SUV">
                            <p>Toyota Fortuner is a robust SUV that combines luxury and performance.</p>
                            <p>It features a powerful engine, spacious interior, and advanced safety features.</p>
                        </div>
                        <div class="car-details">
                            <span>
                                <i class="fas fa-user"></i> 7
                            </span>
                            <span>
                                <i class="fas fa-car"></i> AT
                            </span>
                            <span>
                                <i class="fas fa-gas-pump"></i> GASOLINE
                            </span>
                        </div>
                        <div class="price">Price: ₱ 2500/day <br>Price: ₱ 104/hour</div> <!-- Price included -->
                    </div>
                </a>
            </div>
            <a href="vehicles.php" class="btn-primary btn-bottom-right">View More</a> <!-- Button added here -->
        </section>

        <section class="story">
            <h2>Our Story</h2>
            <p>
                For years, we’ve been your trusted partner in car rentals, offering reliable, affordable, and premium vehicles for every journey. From family trips to corporate travel, our diverse fleet ensures comfort and convenience, no matter the destination.
            </p>
            <p>
                With a focus on customer satisfaction, we provide top-notch service, seamless booking, and eco-friendly options. Whether exploring the city or venturing into the countryside, we’re here to make your journey unforgettable.
            </p>
            <div class="story-images">
                <img src="rental_fleet.jpg" alt="Rental Fleet">
                <img src="happy_customer.jpg" alt="Happy Customer with Car Rental">
            </div>
        </section>

        <section class="cta">
            <h2>Your Next Travel With Us?</h2>
            <p class="location-info">
                Explore the beautiful region of <strong>Calabarzon</strong> and make memories that will last a lifetime!
            </p>
            <div class="cta-map">
                <img src="path-to-xalabarzon-image.jpg" alt="Calabarzon Landscape" class="cta-image">
                <!-- Optionally, you can replace the image with an embedded map link -->
                <!-- <iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe> -->
            </div>
            <a href="reservation.php" class="btn-primary">Get Started</a>
        </section>
        <?php require ('../chatbox/clientmessage.php'); //include the chat ?> 
    </body>
</html>
