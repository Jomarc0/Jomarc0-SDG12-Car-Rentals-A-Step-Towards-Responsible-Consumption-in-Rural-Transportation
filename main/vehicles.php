<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MY Car Rental</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
  <style>
    /* General Styles */
    body {
  font-family: 'Arial', sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f0f4f8; /* Light background for contrast */
}

main {
  padding: 2rem;
}

/* Car Container */
.car-container {
  max-width: 1500px;
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  padding-top: 10rem;
  gap: 2rem; 
}

/* Car Card */
.car-card {
  position: relative; 
  background-color: #ffffff; /* White background for cards */
  border-radius: 12px; 
  border: 1px solid #4F5576; /* Use #4F5576 for the border */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
  padding: 1.5rem; 
  width: 400px; 
  transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease; 
  text-decoration: none;
  color: inherit;
}

.car-card:hover {
  background-color: #D5DFF2; /* Change background color on hover */
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); 
  transform: translateY(-2px); 
}

/* Car Image */
.car-image {
  width: 100%; 
  height: 180px; 
  object-fit: cover; 
  border-radius: 12px; 
  margin-bottom: 1rem; 
}

/* Price */
.price {
  position: absolute; 
  top: 0; 
  left: 0; 
  right: 0; 
  bottom: 0; 
  display: flex; 
  justify-content: center; 
  align-items: center; 
  font-size: 1.5rem; 
  font-weight: bold; 
  color: #ffffff; 
  background-color: rgba(24, 27, 38, 0.4); /* Dark semi-transparent background */
  opacity: 0; /* Initially hidden */
  transform: scale(0.8); /* Start smaller */
  transition: opacity 0.3s ease, transform 0.3s ease; 
}

.car-card:hover .price {
  opacity: 1; 
  transform: scale(1); 
}

.car-info {
  margin-top: 1rem;
}

.car-title {
  font-size: 1.5rem;
  font-weight: bold;
  color: #181b26; /* Dark color for title */
}

.car-details {
  display: flex;
  justify-content: space-between;
  margin-top: 0.5rem;
  font-size: 0.9rem; 
  color: #4F5576; /* Lighter color for details */
}

.car-details span {
  display: flex;
  align-items: center;
}

.info-popup {
  display: none; 
  position: absolute;
  left: 50%; 
  top: -80%; 
  transform: translateX(-50%); 
  background-color: #ffffff; /* White background for popup */
  border: 1px solid #4F5576; /* Use #4F5576 for the border */
  padding: 20px;
  width: 350px; 
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
  z-index: 10; 
}

/* Show Info Popup on Hover */
.car-card:hover .info-popup {
  display: block; 
}
  </style>
</head>

<body>
  <?php include('../header/header.php'); ?>
  <main>
  <form action="">
      <div class="car-container">
        <a href="reservation.php" class="car-card">
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
            <div class="price">Price: $50/day</div> <!-- Price included -->
          </div>
        </a>

        <a href="reservation.php" class="car-card">
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
            <div class="price">Price: $70/day</div> <!-- Price included -->
          </div>
        </a>

        <a href="reservation.php" class="car-card">
          <img class="car-image" src="../pictures/mid size suv.png" alt="Midsize SUV">
          <div class="car-info">
            <h2 class="car-title">Midsize SUV</h2>
            <span>Toyota Raize</span>
            <i class="fa-solid fa-circle-info info-icon"></i>
            <div class="info-popup">
              <img class="car-image" src="../pictures/mid size suv.png" alt="Midsize SUV">
              <p>Toyota Raize is a versatile SUV that offers a blend of style and functionality.</p>
              <p>It features a compact design, spacious interior, and modern technology.</p>
            </div>
            <div class="car-details">
              <span>
                <i class="fas fa-user"></i> 5
              </span>
              <span>
                <i class="fas fa-car"></i> AT
              </span>
              <span>
                <i class="fas fa-gas-pump"></i> GASOLINE
              </span>
            </div>
            <div class="price">Price: $60/day</div> <!-- Price included -->
          </div>
        </a>

        <a href="reservation.php" class="car-card">
          <img class="car-image" src="../pictures/pick up.png" alt="Pick-up">
          <div class="car-info">
            <h2 class="car-title">Pick Up</h2>
            <span>Toyota Hilux</span>
            <i class="fa-solid fa-circle-info info-icon"></i>
            <div class="info-popup">
              <img class="car-image" src ="../pictures/pick up.png" alt="Pick-up">
              <p>Toyota Hilux is a durable pick-up truck designed for both work and leisure.</p>
              <p>It features a powerful engine, spacious cargo area, and advanced safety features.</p>
            </div>
            <div class="car-details">
              <span>
                <i class="fas fa-user"></i> 4
              </span>
              <span>
                <i class="fas fa-car"></i> AT
              </span>
              <span>
                <i class="fas fa-gas-pump"></i> GASOLINE
              </span>
            </div>
            <div class="price">Price: $65/day</div> <!-- Price included -->
          </div>
        </a>

        <a href="reservation.php" class="car-card">
          <img class="car-image" src="../pictures/subcompact.jpg" alt="Subcompact Sedan">
          <div class="car-info">
            <h2 class="car-title">Subcompact Sedan</h2>
            <span>Toyota Wigo</span>
            <i class="fa-solid fa-circle-info info-icon"></i>
            <div class="info-popup">
              <img class="car-image" src="../pictures/subcompact.jpg" alt="Subcompact Sedan">
              <p>Toyota Wigo is a compact car that offers great fuel efficiency and maneuverability.</p>
              <p>It features a stylish design, comfortable interior, and modern technology.</p>
            </div>
            <div class="car-details">
              <span>
                <i class="fas fa-user"></i> 4
              </span>
              <span>
                <i class="fas fa-car"></i> AT
              </span>
              <span>
                <i class="fas fa-gas-pump"></i> GASOLINE
              </span>
            </div>
            <div class="price">Price: $40/day</div> <!-- Price included -->
          </div>
        </a>

        <a href="reservation.php" class="car-card">
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
            <div class="price">Price: $90/day</div> <!-- Price included -->
          </div>
        </a>

        <a href="reservation.php" class="car-card">
          <img class="car-image" src="../pictures/Sports Car.jpg" alt="Sports Car">
          <div class="car-info">
            <h2 class="car-title">Sports Car</h2>
            <span>Toyota 86</span>
            <i class="fa-solid fa-circle-info info-icon"></i>
            <div class="info-popup">
              <img class="car-image" src="../pictures/Sports Car.jpg" alt="Sports Car">
              <p>Toyota 86 is a sporty coupe that delivers thrilling performance and handling.</p>
              <p>It features a powerful engine, sleek design, and advanced technology.</p>
            </div>
            <div class="car-details">
              <span>
                <i class="fas fa-user"></i> 2
              </span>
              <span>
                <i class="fas fa-car"></i> AT
              </span>
              <span>
                <i class="fas fa-gas-pump"></i> GASOLINE
              </span>
            </div>
            <div class="price">Price: $120/day</div> <!-- Price included -->
          </div>
        </a>
      </div>
    </form>
  </main>
  <?php require ('../chatbox/clientmessage.php'); //include the chat ?> 
  <script src="../js/vehicle.js"></script>

</body>
</html>