<?php
include "config.php";
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location:adminlogin.php");
    exit();
}

date_default_timezone_set("Asia/Kolkata");

$today = date("Y-m-d");

/* ===================== BOOKINGS ===================== */
$todayBooking = mysqli_num_rows(mysqli_query($conn,
"SELECT id FROM bookings WHERE DATE(created_at)='$today'"));

$totalBooking = mysqli_num_rows(mysqli_query($conn,
"SELECT id FROM bookings"));

/* ===================== REVENUE ===================== */

// Today Confirmed
$todayConfirmed = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(total_price) as t FROM bookings 
WHERE DATE(created_at)='$today' AND status='Confirmed'"))['t'] ?? 0;

// Today Estimated
$todayEstimated = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(total_price) as t FROM bookings 
WHERE DATE(created_at)='$today'"))['t'] ?? 0;

// Total Confirmed
$totalConfirmed = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(total_price) as t FROM bookings 
WHERE status='Confirmed'"))['t'] ?? 0;

/* ===================== VEHICLE CHART ===================== */
$vehicleChart = mysqli_query($conn,"
SELECT V.name as vehicle_name, COUNT(B.id) as total
FROM bookings B
LEFT JOIN vehicles V ON B.vehicle_id = V.id
GROUP BY V.id
");

$vehicleNames = [];
$vehicleCounts = [];

while($row = mysqli_fetch_assoc($vehicleChart)){
    $vehicleNames[] = $row['vehicle_name'];
    $vehicleCounts[] = $row['total'];
}

/* ===================== MONTHLY CHART ===================== */
$chartData = mysqli_query($conn,"
SELECT 
MONTH(created_at) as month,
SUM(CASE WHEN status='Confirmed' THEN total_price ELSE 0 END) as confirmed,
SUM(total_price) as estimated
FROM bookings
GROUP BY MONTH(created_at)
");

$months = [];
$confirmedData = [];
$estimatedData = [];

while($row = mysqli_fetch_assoc($chartData)){
    $months[] = date("M", mktime(0,0,0,$row['month'],1));
    $confirmedData[] = $row['confirmed'];
    $estimatedData[] = $row['estimated'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body { background:#f4f6f9; }

.sidebar {
  height:100vh;
  width:220px;
  position:fixed;
  background:#212529;
  color:white;
}

.sidebar a {
  display:block;
  color:white;
  padding:12px;
  text-decoration:none;
}

.sidebar a:hover { background:#343a40; }

.content { margin-left:220px; padding:20px; }

.card-box {
  padding:15px;
  border-radius:10px;
  color:white;
}

.bg1{background:#667eea;}
.bg2{background:#ff416c;}
.bg3{background:#28a745;}
.bg4{background:#ffc107;}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <h4 class="text-center mt-3">Admin</h4>
  <a href="dashboard.php">📊 Dashboard</a>
  <a href="vehicle_admin.php">🚗 Vehicles</a>
  <a href="admin_city.php">🏙 City</a>
  <a href="admin_brand.php">🏷 Brand</a>
  <a href="adminlogout.php">🚪 Logout</a>
</div>

<div class="content">

<h4>📊 Dashboard</h4>

<!-- CARDS -->
<div class="row g-3">

<div class="col-md-3">
<div class="card-box bg1">
<h5><?= $todayBooking ?></h5>
<small>Today Booking</small>
</div>
</div>

<div class="col-md-3">
<div class="card-box bg2">
<h5><?= $totalBooking ?></h5>
<small>Total Booking</small>
</div>
</div>

<div class="col-md-3">
<div class="card-box bg3">
<h5>₹<?= $todayConfirmed ?></h5>
<small>Today Confirmed Revenue</small>
</div>
</div>

<div class="col-md-3">
<div class="card-box bg4">
<h5>₹<?= $todayEstimated ?></h5>
<small>Today Estimated Revenue</small>
</div>
</div>

<div class="col-md-3 mt-3">
<div class="card-box bg3">
<h5>₹<?= $totalConfirmed ?></h5>
<small>Total Confirmed Revenue</small>
</div>
</div>

</div>

<!-- CHARTS -->
<div class="row mt-4">

<!-- Vehicle Chart -->
<div class="col-md-6">
<div class="card p-3">
<h6>🚗 Vehicle Wise Bookings</h6>
<canvas id="vehicleChart"></canvas>
</div>
</div>

<!-- Revenue Chart -->
<div class="col-md-6">
<div class="card p-3">
<h6>💰 Monthly Revenue (Confirmed vs Estimated)</h6>
<canvas id="revenueChart"></canvas>
</div>
</div>

</div>

<!-- TABLE -->
<div class="card mt-4 p-3">

<h5>📋 Booking Details</h5>

<table class="table table-bordered mt-3">

<tr class="table-dark">
<th>#</th>
<th>User</th>
<th>Vehicle</th>
<th>City</th>
<th>Status</th>
<th>Pickup</th>
<th>Return</th>
<th>Amount</th>
</tr>

<?php
$sql = "
SELECT B.*, V.name as vehicle_name, C.city, U.name as user_name
FROM bookings B
LEFT JOIN vehicles V ON B.vehicle_id = V.id
LEFT JOIN city_master C ON V.city = C.id
LEFT JOIN users U ON B.user_id = U.id
ORDER BY B.id DESC
";

$res = mysqli_query($conn,$sql);
$i=1;

while($row = mysqli_fetch_assoc($res)){
?>

<tr>
<td><?= $i++ ?></td>
<td><?= $row['user_name'] ?></td>
<td><?= $row['vehicle_name'] ?></td>
<td><?= $row['city'] ?></td>
<td><?= $row['status'] ?></td>
<td><?= $row['pickup_date'] ?></td>
<td><?= $row['return_date'] ?></td>
<td>₹<?= $row['total_price'] ?></td>
</tr>

<?php } ?>

</table>

</div>

</div>

<script>

// VEHICLE CHART
new Chart(document.getElementById("vehicleChart"), {
  type: 'bar',
  data: {
    labels: <?= json_encode($vehicleNames) ?>,
    datasets: [{
      label: "Bookings",
      data: <?= json_encode($vehicleCounts) ?>,
      backgroundColor: "#667eea"
    }]
  }
});

// REVENUE CHART
new Chart(document.getElementById("revenueChart"), {
  type: 'bar',
  data: {
    labels: <?= json_encode($months) ?>,
    datasets: [
      {
        label: "Confirmed Revenue",
        data: <?= json_encode($confirmedData) ?>,
        backgroundColor: "#28a745"
      },
      {
        label: "Estimated Revenue",
        data: <?= json_encode($estimatedData) ?>,
        backgroundColor: "#ffc107"
      }
    ]
  }
});

</script>

</body>
</html>