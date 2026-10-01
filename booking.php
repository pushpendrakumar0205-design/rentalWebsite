<?php
include "config.php";
session_start();
?>




<?php
if (isset($_GET['vehicle_id'])) {
  $id = intval($_GET['vehicle_id']);

  $sql = "SELECT *
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

// ✅ BOOKING INSERT
if (isset($_POST['confirm_booking'])) {

  $vehicle_id = $id;
  $pickup_date = $_POST['startDate'];
  $return_date = $_POST['endDate'];
  $location = $_POST['pickupLocation'];
  $days = $_POST['days'];
  $total = $_POST['total'];

  $user_id = $_SESSION['user'];

  $sql = "INSERT INTO bookings 
(vehicle_id, user_id, pickup_date, return_date, location, days, total_price) 
VALUES 
('$vehicle_id','$user_id','$pickup_date','$return_date','$location','$days','$total')";

  mysqli_query($conn, $sql);

  echo "<script>alert('Booking Confirmed'); window.location='booking_history.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Book Vehicle</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

 <style>
  body { background:#eef1f7; font-family:sans-serif; }

.navbar { background:#000; }
.nav-link { color:#ccc !important; margin-left:15px; }
.nav-link:hover { color:#fff !important; }

.vehicle-card{
    background:#fff;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
    padding:15px;
    height:100%;
}

.vehicle-img{
    width:100%;
    height:320px;
    object-fit:cover;
    border-radius:12px;
}

/* MOBILE RESPONSIVE */
@media(max-width:768px){

    .vehicle-img{
        height:220px;
    }

    .vehicle-title{
        font-size:22px;
        margin-top:10px;
    }

    .vehicle-location{
        font-size:14px;
    }

    .vehicle-price{
        font-size:20px;
    }
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
    <div class="container-box">

      <div class="row">

        <!-- LEFT -->
        <div class="col-md-6">
          <img src="uploads/<?php echo $row['image']; ?>" class="vehicle-img mb-3">

          <h4><?php echo $row['brand']; ?> <?php echo $row['name']; ?></h4>
          <p class="text-muted">📍 <?php echo $row['city']; ?></p>
          <h5 class="text-success">₹<?php echo $row['price']; ?> / day</h5>
        </div>

        <!-- RIGHT -->
        <div class="col-md-6">

          <label>Pickup Date</label>
          <input type="date" id="startDate" class="form-control mb-3">

          <label>Return Date</label>
          <input type="date" id="endDate" class="form-control mb-3">

          <label>Pickup Location</label>
          <input type="text" id="pickupLocation" class="form-control mb-3" placeholder="Enter location">

          <button onclick="calculatePrice()" class="btn btn-dark w-100 mb-3">
            Calculate Price
          </button>

          <div class="price-box">
            <p>Price per day: ₹<?php echo $row['price']; ?></p>
            <p>Total days: <span id="days">0</span></p>
            <h5>Total: ₹<span id="total">0</span></h5>


            <?php if (isset($_SESSION['user'])) { ?>
              <?php
              $_SESSION['user'];
              $user_id = $_SESSION['user'];

              $user_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
              $user = mysqli_fetch_assoc($user_query);
              ?>
              <button onclick="openSummary()" class="btn btn-success w-100 mt-3">
                Confirm Booking
              </button>

            <?php } else { ?>

              <a href="loginpage.php.?vehicle_id=<?php echo $id ?>"
                class="btn btn-success w-100 mt-3">
                Login To Book
              </a>

            
            <?php } ?>

          </div>

        </div>

      </div>
    </div>
  </div>

  <!-- MODAL -->
  <div class="modal fade" id="summaryModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5>Booking Summary</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <p><b>Name:</b> <?php echo $user['name']; ?></p>
          <p><b>Mobile:</b> <?php echo $user['mobile']; ?></p>
          <p><b>Vehicle:</b> <?php echo $row['brand']; ?> <?php echo $row['name']; ?></p>
          <p><b>Pickup:</b> <span id="sumStart"></span></p>
          <p><b>Return:</b> <span id="sumEnd"></span></p>
          <p><b>Location:</b> <span id="sumLocation"></span></p>
          <p><b>Days:</b> <span id="sumDays"></span></p>
          <h5>Total: ₹<span id="sumTotal"></span></h5>
        </div>

        <div class="modal-footer">

          <form method="post">
            <input type="hidden" name="startDate" id="inputStart">
            <input type="hidden" name="endDate" id="inputEnd">
            <input type="hidden" name="pickupLocation" id="inputLocation">
            <input type="hidden" name="days" id="inputDays">
            <input type="hidden" name="total" id="inputTotal">

            <button type="submit" name="confirm_booking" class="btn btn-success">
              Confirm Booking
            </button>
          </form>

        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function calculatePrice() {

      let start = document.getElementById("startDate").value;
      let end = document.getElementById("endDate").value;
      let location = document.getElementById("pickupLocation").value;

      if (start == "" || end == "" || location == "") {
        alert("Fill all fields");
        return;
      }

      let d1 = new Date(start);
      let d2 = new Date(end);

      let diff = (d2 - d1) / (1000 * 60 * 60 * 24);

      if (diff <= 0) {
        alert("Invalid dates");
        return;
      }

      let price = <?php echo $row['price']; ?>;
      let total = diff * price;

      document.getElementById("days").innerText = diff;
      document.getElementById("total").innerText = total;
    }

    function openSummary() {

      let start = document.getElementById("startDate").value;
      let end = document.getElementById("endDate").value;
      let location = document.getElementById("pickupLocation").value;
      let days = document.getElementById("days").innerText;
      let total = document.getElementById("total").innerText;

      if (days == 0) {
        alert("Calculate price first");
        return;
      }

      document.getElementById("sumStart").innerText = start;
      document.getElementById("sumEnd").innerText = end;
      document.getElementById("sumLocation").innerText = location;
      document.getElementById("sumDays").innerText = days;
      document.getElementById("sumTotal").innerText = total;

      // hidden inputs
      document.getElementById("inputStart").value = start;
      document.getElementById("inputEnd").value = end;
      document.getElementById("inputLocation").value = location;
      document.getElementById("inputDays").value = days;
      document.getElementById("inputTotal").value = total;

      let modal = new bootstrap.Modal(document.getElementById('summaryModal'));
      modal.show();
    }
  </script>
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