<?php
include "config.php";
?>
<?php
session_start();


if (isset($_GET['vehicle_id'])) {

  $id = intval($_GET['vehicle_id']);
  $sql = "SELECT *, C.city, B.brand ,V.id as vehicle_id
            FROM vehicles as V 
            LEFT JOIN city_master as C ON V.city = C.id 
            LEFT JOIN brand_master as B ON V.brand = B.id 
          WHERE V.id = '$id'";

  $res = mysqli_query($conn, $sql);

  if (mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
  } else {
    die("Vehicle not found");
  }
} else {
  die("Invalid Request");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vehicle Details</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #eef1f7;
    }

    /* NAVBAR */
    .navbar {
      backdrop-filter: blur(10px);
      background: rgba(0, 0, 0, 0.7);
    }

    .navbar-brand {
      font-size: 24px;
      font-weight: 600;
      color: #fff !important;
    }

    .nav-link {
      color: #ccc !important;
      margin-left: 15px;
      transition: 0.3s;
    }

    .nav-link:hover {
      color: #fff !important;
    }


    /* Card */
    .vehicle-card {
      background: #fff;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    /* Image */
    .vehicle-img {
      width: 100%;
      height: 350px;
      object-fit: cover;
      border-radius: 15px;
    }

    /* Title */
    .vehicle-title {
      font-size: 28px;
      font-weight: 600;
    }

    /* Price */
    .price {
      font-size: 24px;
      color: #28a745;
      font-weight: bold;
    }

    /* Specs */
    .spec-box {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 10px;
    }

    .spec-box p {
      margin: 5px 0;
    }

    /* Button */
    .btn-book {
      background: linear-gradient(45deg, #007bff, #00c6ff);
      border: none;
      border-radius: 10px;
      color: white;
      font-size: 18px;
      padding: 10px;
    }

    .btn-book:hover {
      opacity: 0.9;
    }

    /* Badge */
    .badge-city {
      background: #6c757d;
      padding: 6px 10px;
      border-radius: 8px;
      color: white;
      font-size: 14px;
    }
  </style>

</head>

<body>

 

  <nav class="navbar navbar-expand-lg">
<div class="container">
  <a class="navbar-brand text-white" href="#">🚗 RentRide</a>

  <div class="ms-auto">
    <a class="nav-link d-inline" href="index.php">Home</a>

    <?php if (isset($_SESSION['user'])) { ?>
      <a class="nav-link d-inline" href="booking_history.php">My Bookings</a>
      <a class="nav-link d-inline" href="userlogout.php">Logout</a>
    <?php } else { ?>
      <a class="nav-link d-inline" href="login.html">Login</a>
      <a class="nav-link d-inline" href="signup.html">Signup</a>
      <a class="nav-link d-inline" href="dashboard.php">Admin Login</a>

    <?php } ?>
  </div>
</div>
</nav>

  <div class="container my-5">

    <div class="vehicle-card">

      <div class="row">

        <!-- Left Image -->
        <div class="col-md-6">
          <img src="uploads/<?php echo $row['image']; ?>" class="vehicle-img">
        </div>

        <!-- Right Details -->
        <div class="col-md-6">

          <span class="badge-city">📍 <?php echo $row['city']; ?></span>

          <h3 class="vehicle-title mt-2">
            <?php echo $row['brand']; ?> - <?php echo $row['name']; ?>
          </h3>

          <p class="price">₹<?php echo $row['price']; ?> / day</p>

          <p class="text-muted">
            Perfect vehicle for city rides and long trips. Comfortable & reliable.
          </p>

          <!-- Specs -->
          <div class="spec-box mb-3">
            <p>⚙️ <b>Engine:</b> <?php echo $row['engine']; ?></p>
            <p>⛽ <b>Fuel:</b> <?php echo $row['fuel']; ?></p>
            <p>📊 <b>Mileage:</b> <?php echo $row['mileage']; ?> KM/L</p>
          </div>

          <!-- Button -->
          <a href="booking.php?vehicle_id=<?php echo $row['vehicle_id']; ?>"
            class="btn btn-book w-100">
            🚗 Book Now
          </a>

        </div>

      </div>

    </div>

  </div>
  <!-- FOOTER -->
<footer style="background:#000; color:#fff; padding:50px 0 20px; margin-top:50px;">

<div class="container">

<div class="row">

 
<hr style="border-color:#444;">

<div class="text-center">
    <p style="color:#aaa; margin:0;">
        © 2026 RentRide | All Rights Reserved | Designed By Pushpendra
    </p>
</div>

</div>

</footer>
</body>

</html>