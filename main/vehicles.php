<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MY Car Rental</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
  <link rel="stylesheet" href="../css/vehicles.css">

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
            <div class="price">Price: ₱ 1500/day <br>Price: ₱ 63/hour</div> <!-- Price included -->
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
            <div class="price">Price: ₱ 2500/day <br>Price: ₱ 104/hour</div> <!-- Price included -->
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
            <div class="price">Price: ₱ 200/day <br>Price: ₱ 83/hour</div> <!-- Price included -->
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
            <div class="price">Price: ₱ 3500/day <br>Price: ₱ 146/hour</div> 
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
            <div class="price">Price: ₱ 1500/day <br>Price: ₱ 63/hour</div> 
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
            <div class="price">Price: ₱ 4000/day <br>Price: ₱ 167/hour</div> <!-- Price included -->
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
            <div class="price">Price: ₱ 5500/day <br>Price: ₱ 229/hour</div> <!-- Price included -->
          </div>
        </a>
        <a href="reservation.php" class="car-card">
          <img class="car-image" src="../pictures/tesla.png" alt="Tesla">
          <div class="car-info">
            <h2 class="car-title">Tesla</h2>
            <span>Tesla X</span>
            <i class="fa-solid fa-circle-info info-icon"></i>
            <div class="info-popup">
              <img class="car-image" src="../pictures/tesla.png" alt="Tesla">
              <p>All new Tesla cars have the capability for full self-driving.</p>
              <p> Intended to enhance safety and make driving easier, the autopilot feature can steer, accelerate and brake, all on its own. </p>
              <p>There's also a Smart Summon feature that's a part of the Tesla autopilot and lets you park or retrieve your car using a command button</p>
            </div>
            <div class="car-details">
              <span>
                <i class="fas fa-user"></i> 4
              </span>
              <span>
                <i class="fas fa-car"></i> Auto Drive
              </span>
              <span>
                <i class="fas fa-gas-pump"></i> Electric
              </span>
            </div>
            <div class="price">Price: ₱ 6500/day <br>Price: ₱ 270/hour</div> <!-- Price included -->
          </div>
        </a>
      </div>
    </form>
  </main>
  <?php require ('../chatbox/clientmessage.php'); //include the chat ?> 
  <script src="../js/vehicle.js"></script>

</body>
</html>