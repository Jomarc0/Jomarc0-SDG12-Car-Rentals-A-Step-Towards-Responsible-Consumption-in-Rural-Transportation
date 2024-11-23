<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MY Car Rental</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
  <link rel="stylesheet" href="../css/vehicle.css">
  <link rel="stylesheet" href="../css/header.css">
</head>

<body>
  <?php include('../header/header.php');?>
    <main>
      <form action="">
        <div class="car-container">
          <div class="car-card">
            <img class="car-image" src="../pictures/sedan.jpg" alt="Economy Sedan">
            <div class="car-info">
              <h2 class="car-title">Sedan</h2>
              <span> Toyota Vios</span> 
              <i class="fa-solid fa-circle-info" id="info-icon"></i>
                <div class="info-popup" id="info-popup">
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
                    <i class="fa-duotone fa-solid fa-gas-pump"></i> GASOLINEs
                    </span>
                  </div>
                  <a href="reservation.php"><button class="car-button"> Book Now</button></a>

                </div>
              </div>

          <div class="car-card">
            <img class="car-image" src="../pictures/Full size suv.jpg" alt="Fullsize SUV">
            <div class="car-info">
              <h2 class="car-title">Fullsize SUV</h2>
              <span> Toyota Fortuner </span>
              <i class="fa-solid fa-circle-info"></i>
              <div class="info-popup" id="info-popup">
                <img class="car-image" src="../pictures/Full size suv.jpg" alt="Fullsize SUV">
                <p>Toyota Vios is a compact sedan that offers a comfortable ride and excellent fuel efficiency.</p>
                <p>It features a 1.5L engine, automatic transmission, and a range of safety features.</p>
                </div>
                <div class="car-details">
                  <span>
                    <i class="fas fa-user"></i> 7
                  </span>
                  <span>
                    <i class="fas fa-car"></i> AT
                  </span>
                  <span>
                  <i class="fa-solid fa-gas-pump"></i> GASOLINE
                  </span>
                </div>
                <button class="car-button">Book Now</button>
              </div>
            </div>

          <div class="car-card">
            <img class="car-image" src="../pictures/mid size suv.png" alt="Midsize SUV">
            <div class="car-info">
              <h2 class="car-title">Midsize SUV</h2>
              <span> Toyota Raize </span>
              <i class="fa-solid fa-circle-info"></i>
              <div class="info-popup" id="info-popup">
                <img class="car-image" src="../pictures/mid size suv.png" alt="Midsize SUV">
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
                  <i class="fa-solid fa-gas-pump"></i> GASOLINE
                  </span>
                </div>
                <button class="car-button">Book Now</button>
              </div>
            </div>

          <div class="car-card">
            <img class="car-image" src="../pictures/pick up.png" alt="Pick-up">
            <div class="car-info">
              <h2 class="car-title">Pick Up</h2>
              <span> Toyota Hilux</span>
              <i class="fa-solid fa-circle-info"></i>
              <div class="info-popup" id="info-popup">
                <img class="car-image" src="../pictures/pick up.png" alt="Pick-up">
                <p>Toyota Vios is a compact sedan that offers a comfortable ride and excellent fuel efficiency.</p>
                <p>It features a 1.5L engine, automatic transmission, and a range of safety features.</p>
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
                  <button class="car-button">Book Now</button>
                </div>
              </div>

          <div class="car-card">
            <img class="car-image" src="../pictures/subcompact.jpg" alt="Subcompact Sedan">
            <div class="car-info">
              <h2 class="car-title">Subcompact Sedan</h2>
              <span> Toyota Wigo </span>
              <i class="fa-solid fa-circle-info"></i>
              <div class="info-popup" id="info-popup">
                <img class="car-image" src="../pictures/subcompact.jpg" alt="Subcompact Sedan">
                <p>Toyota Vios is a compact sedan that offers a comfortable ride and excellent fuel efficiency.</p>
                <p>It features a 1.5L engine, automatic transmission, and a range of safety features.</p>
                </div>
                  <div class="car-details">
                    <span>
                      <i class="fas fa-user"></i> 4
                    </span>
                    <span>
                      <i class="fas fa-car"></i> AT
                    </span>
                    <span>
                    <i class="fa-solid fa-gas-pump"></i> GASOLINE
                    </span>
                  </div>
                  <button class="car-button">Book Now</button>
                </div>
              </div>

          <div class="car-card">
            <img class="car-image" src="../pictures/VAN.jpg" alt="Van">
            <div class="car-info">
              <h2 class="car-title">Van</h2>
              <span> Toyota Hiace </span>
              <i class="fa-solid fa-circle-info"></i>
              <div class="info-popup" id="info-popup">
                <img class="car-image" src="../pictures/VAN.jpg" alt="Van">
                <p>Toyota Vios is a compact sedan that offers a comfortable ride and excellent fuel efficiency.</p>
                <p>It features a 1.5L engine, automatic transmission, and a range of safety features.</p>
                </div>
                  <div class="car-details">
                    <span>
                      <i class="fas fa-user"></i> 15
                    </span>
                    <span>
                      <i class="fas fa-car"></i> AT
                    </span>
                    <span>
                    <i class="fa-solid fa-gas-pump"></i> GASOLINE
                    </span>
                  </div>
                  <button class="car-button">Book Now</button>
                </div>
              </div>
          
          <div class="car-card">
            <img class="car-image" src="../pictures/Sports Car.jpg" alt="Sports Car">
            <div class="car-info">
              <h2 class="car-title">Sports Car</h2>
              <span> Toyota 86 </span>
              <i class="fa-solid fa-circle-info"></i>
              <div class="info-popup" id="info-popup">
                <img class="car-image" src="../pictures/Sports Car.jpg" alt="Sports Car">
                <p>Toyota Vios is a compact sedan that offers a comfortable ride and excellent fuel efficiency.</p>
                <p>It features a 1.5L engine, automatic transmission, and a range of safety features.</p>
                </div>
                  <div class="car-details">
                    <span>
                      <i class="fas fa-user"></i> 2
                    </span>
                    <span>
                      <i class="fas fa-car"></i> AT
                    </span>
                    <span>
                    <i class="fa-solid fa-gas-pump"></i> GASOLINE
                    </span>
                  </div>
                  <button class="car-button">Book Now</button>
                </div>
              </div>
            </div>
          </form>
        </main>
        <?php require ('../chatbox/clientmessage.php'); //include the chat ?> 
<script src="../js/vehicle.js"></script>
</body>
</html>