<?php
include "config.php";
session_start();

// ================== SEARCH ==================
$cond = " WHERE 1=1 ";

if (isset($_GET['search'])) {
  $city = $_GET['city'];
  $brand = $_GET['brand'];

  if (!empty($city)) {
    $cond .= " AND V.city = '$city'";
  }

  if (!empty($brand)) {
    $cond .= " AND V.brand = '$brand'";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RentRide</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#eef1f7; font-family:sans-serif; }

.navbar { background:#000; }
.nav-link { color:#ccc !important; margin-left:15px; }
.nav-link:hover { color:#fff !important; }

.hero {
  height:90vh;
  background:linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.7)),
  url('https://images.unsplash.com/photo-1503376780353-7e6692767b70') center/cover;
  display:flex; align-items:center; text-align:center; color:white;
}

.card:hover { transform:translateY(-8px); transition:0.3s; }
.price { color:#007bff; font-weight:bold; }
.btn-book { background:#28a745; color:white; border:none; }
</style>
</head>

<body>

<!-- NAVBAR -->
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

<!-- HERO -->
<section class="hero">
<div class="container">
  <h1>Drive Your Dream Ride 🚗</h1>
  <p>Best cars & bikes at affordable price</p>

  <!-- SEARCH -->
  <form method="get">
    <div class="bg-white p-4 rounded mt-4 row">

      <div class="col-md-4">
        <select name="city" id="city" class="form-control">
          <option value="">Select City</option>
          <?php
          $res = mysqli_query($conn, "SELECT * FROM city_master");
          while ($row = mysqli_fetch_assoc($res)) {
          ?>
          <option value="<?php echo $row['id']; ?>">
            <?php echo $row['city']; ?>
          </option>
          <?php } ?>
        </select>
        <script>
          document.getElementById("city").value = "<?php echo $city; ?>";
        </script>
      </div>

      <div class="col-md-4">
        <select id="brand" name="brand" class="form-control">
          <option value="">Select Brand</option>
          <?php
          $res = mysqli_query($conn, "SELECT * FROM brand_master");
          while ($row = mysqli_fetch_assoc($res)) {
          ?>
          <option value="<?php echo $row['id']; ?>">
            <?php echo $row['brand']; ?>
          </option>
          <?php } ?>
        </select>
        <script>
          document.getElementById("brand").value = "<?php echo $brand; ?>";
        </script>
        
      </div>

      <div class="col-md-4">
        <button class="btn btn-primary w-100" name="search">Search</button>
      </div>

    </div>
  </form>

</div>
</section>

<!-- VEHICLES -->
<div class="container my-5">
<h2 class="text-center mb-4">Available Vehicles</h2>

<div class="row">

<?php
$sql = "SELECT *, C.city, B.brand, V.id as vehicle_id
        FROM vehicles V
        LEFT JOIN city_master C ON V.city=C.id
        LEFT JOIN brand_master B ON V.brand=B.id
        $cond";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
?>

<div class="col-md-4 mb-4">
<div class="card">

<img src="uploads/<?php echo $row['image']; ?>" height="200">

<div class="card-body">
  <h5><?php echo $row['brand']; ?> - <?php echo $row['name']; ?></h5>
  <p>📍 <?php echo $row['city']; ?></p>
  <p class="price">₹<?php echo $row['price']; ?>/day</p>

  <div class="d-flex justify-content-between">

    <a href="details.php?vehicle_id=<?php echo $row['vehicle_id']; ?>"
       class="btn btn-outline-primary btn-sm">
       View
    </a>
      <a href="booking.php?vehicle_id=<?php echo $row['vehicle_id']; ?>"
         class="btn btn-book btn-sm">
         Book Now
      </a>

  </div>
</div>

</div>
</div>

<?php
  }
} else {
  echo "<p class='text-center text-danger'>No vehicles found</p>";
}
?>

</div>
</div>
<!-- FOOTER -->
<footer style="background:#000; color:#fff; padding:50px 0 20px; margin-top:50px;">

<div class="container">

<div class="row">

    <!-- ABOUT -->
    <div class="col-md-4 mb-4">
        <h4 class="mb-3">🚗 RentRide</h4>
        <p style="color:#ccc;">
            Best car & bike rental service with affordable price.
            Book your dream ride anytime anywhere.
        </p>
    </div>

    <!-- QUICK LINKS -->
    <div class="col-md-4 mb-4">
        <h5 class="mb-3">Quick Links</h5>

        <ul style="list-style:none; padding:0;">
            <li class="mb-2">
                <a href="index.php" style="color:#ccc; text-decoration:none;">🏠 Home</a>
            </li>

            <li class="mb-2">
                <a href="booking_history.php" style="color:#ccc; text-decoration:none;">📋 My Booking</a>
            </li>

            <li class="mb-2">
                <a href="login.html" style="color:#ccc; text-decoration:none;">🔐 Login</a>
            </li>

            <li class="mb-2">
                <a href="signup.html" style="color:#ccc; text-decoration:none;">📝 Signup</a>
            </li>
        </ul>
    </div>

    <!-- CONTACT -->
    <div class="col-md-4 mb-4">
        <h5 class="mb-3">Contact Us</h5>

        <p style="color:#ccc;">📍 Raipur </p>
        <p style="color:#ccc;">📞 +91 9109085923</p>
        <p style="color:#ccc;">✉ support@rentride.com</p>

        <!-- SOCIAL -->
        <div class="mt-3">
            <a href="#" style="color:#fff; font-size:22px; margin-right:15px;">📘</a>
            <a href="#" style="color:#fff; font-size:22px; margin-right:15px;">📸</a>
            <a href="#" style="color:#fff; font-size:22px; margin-right:15px;">🐦</a>
            <a href="#" style="color:#fff; font-size:22px;">▶</a>
        </div>
    </div>

</div>

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