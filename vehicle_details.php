<?php
include "config.php";
session_start();

// ================= VALIDATE ID =================
if (!isset($_GET['id'])) {
  header("location:index.php");
  exit;
}

$id = $_GET['id'];

// ================= FETCH VEHICLE WITH JOIN =================
$query = mysqli_query($conn, "
  SELECT V.*, C.city, B.brand 
  FROM vehicles V
  LEFT JOIN city_master C ON V.city = C.id
  LEFT JOIN brand_master B ON V.brand = B.id
  WHERE V.id='$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
  echo "Vehicle not found";
  exit;
}

// ================= BOOKING LOGIC =================
if (isset($_POST['book'])) {

  $vehicle_id = $id;
  $user = $_SESSION['user'] ?? "guest";

  $from_date = $_POST['from_date'];
  $to_date = $_POST['to_date'];


  if (empty($from_date) || empty($to_date)) {
    echo "<script>alert('Please select dates');</script>";
  } else {

    $price = $data['price'];

    $days = (strtotime($to_date) - strtotime($from_date)) / (60 * 60 * 24);

    if ($days <= 0) {
      echo "<script>alert('Invalid date selection');</script>";
    } else {

      // ===== CHECK DOUBLE BOOKING =====
      $check = mysqli_query($conn, "
        SELECT * FROM bookings 
        WHERE vehicle_id='$vehicle_id'
        AND (
          ('$from_date' BETWEEN from_date AND to_date) OR
          ('$to_date' BETWEEN from_date AND to_date) OR
          (from_date BETWEEN '$from_date' AND '$to_date')
        )
      ");

      if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Vehicle already booked for selected dates');</script>";
      } else {

        $total_amount = $days * $price;

        $insert = "INSERT INTO bookings 
        (vehicle_id, user, from_date, to_date, total_amount) 
        VALUES 
        ('$vehicle_id', '$user', '$from_date', '$to_date', '$total_amount')";

        mysqli_query($conn, $insert);

        echo "<script>alert('Booking Successful! Total = ₹$total_amount'); window.location='index.php';</script>";
      }
    }
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Vehicle Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #eef1f7;
      font-family: 'Poppins', sans-serif;
    }

    .card {
      border-radius: 15px;
      overflow: hidden;
      border: none;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .price {
      color: #007bff;
      font-size: 22px;
      font-weight: bold;
    }

    .btn-book {
      background: linear-gradient(45deg, #28a745, #5cd65c);
      border: none;
    }
  </style>
</head>

<body>

  <div class="container mt-5">

    <!-- VEHICLE DETAILS -->
    <div class="card mb-4">
      <img src="uploads/<?php echo $data['image']; ?>"
        style="height:350px;object-fit:cover;">

      <div class="card-body">
        <h2><?php echo $data['brand']; ?> - <?php echo $data['name']; ?></h2>

        <p class="text-muted">📍 <?php echo $data['city']; ?></p>

        <p class="price">₹<?php echo $data['price']; ?> / day</p>

        <p><?php echo $data['description'] ?? "No description available"; ?></p>
      </div>
    </div>

    <!-- BOOKING FORM -->
    <div class="card p-4">
      <h4 class="mb-3">Book This Vehicle</h4>

      <form method="post">
        <div class="row">

          <div class="col-md-4">
            <label>From Date</label>
            <input type="date" name="from_date" class="form-control" required
              onchange="calculatePrice(<?php echo $data['price']; ?>)">
          </div>

          <div class="col-md-4">
            <label>To Date</label>
            <input type="date" name="to_date" class="form-control" required
              onchange="calculatePrice(<?php echo $data['price']; ?>)">
          </div>

          <div class="col-md-4 d-flex align-items-end">
            <button type="submit" name="book" class="btn btn-book w-100 text-white">
              Confirm Booking
            </button>
          </div>

        </div>

        <div class="mt-3">
          <h5>Total Price: <span id="total">₹0</span></h5>
        </div>

      </form>
    </div>

  </div>

  <!-- PRICE SCRIPT -->
  <script>
    function calculatePrice(price) {

      let from = document.querySelector('[name="from_date"]').value;
      let to = document.querySelector('[name="to_date"]').value;

      if (from && to) {

        let d1 = new Date(from);
        let d2 = new Date(to);

        let days = (d2 - d1) / (1000 * 60 * 60 * 24);

        if (days > 0) {
          document.getElementById("total").innerText = "₹" + (days * price);
        } else {
          document.getElementById("total").innerText = "₹0";
        }
      }
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