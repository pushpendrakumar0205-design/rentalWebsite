<?php
include "config.php";
session_start();

// ================= LOGIN CHECK =================
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$user_id = intval($_SESSION['user']);

// ================= FETCH USER =================
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

// ================= FETCH BOOKINGS =================
$sql = "SELECT B.*, 
               V.name, 
               V.image,
               C.city, 
               BR.brand
        FROM bookings B
        JOIN vehicles V ON B.vehicle_id = V.id
        LEFT JOIN city_master C ON V.city = C.id
        LEFT JOIN brand_master BR ON V.brand = BR.id
        WHERE B.user_id = '$user_id'
        ORDER BY B.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Bookings</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f7fb;
    font-family:'Segoe UI';
}

/* NAVBAR */
.navbar{
    background:#111;
    padding:15px 0;
}

.navbar-brand{
    font-size:24px;
    font-weight:bold;
}

.nav-link{
    color:#ddd !important;
    margin-left:15px;
}

.nav-link:hover{
    color:#fff !important;
}

.user-name{
    color:#00ffcc !important;
    font-weight:bold;
}

/* PAGE TITLE */
.page-title{
    text-align:center;
    margin-bottom:40px;
}

.page-title h2{
    font-weight:700;
}

/* CARD */
.booking-card{
    border:none;
    border-radius:18px;
    overflow:hidden;
    transition:0.3s;
    background:#fff;
}

.booking-card:hover{
    transform:translateY(-6px);
    box-shadow:0 15px 35px rgba(0,0,0,0.1);
}

.booking-card img{
    height:220px;
    width:100%;
    object-fit:cover;
}

.card-body{
    padding:20px;
}

.vehicle-title{
    font-size:20px;
    font-weight:700;
}

.location{
    color:#777;
    font-size:14px;
}

/* STATUS */
.badge{
    padding:8px 14px;
    font-size:13px;
    border-radius:20px;
}

/* EMPTY */
.empty-box{
    background:#fff;
    padding:60px;
    border-radius:15px;
    text-align:center;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

/* FOOTER */
footer{
    background:#000;
    color:#fff;
    padding:40px 0 20px;
    margin-top:50px;
}

footer p{
    color:#aaa;
    margin:0;
}

@media(max-width:768px){

    .navbar .d-flex{
        flex-direction:column;
        align-items:flex-start !important;
    }

    .nav-link{
        margin-left:0;
        margin-top:10px;
    }
}

</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
<div class="container">

<a class="navbar-brand" href="index.php">
🚗 RentRide
</a>

<div class="d-flex align-items-center">

<a class="nav-link" href="index.php">Home</a>

<?php if(isset($_SESSION['user'])){ ?>

<a class="nav-link" href="booking_history.php">
My Bookings
</a>

<span class="nav-link user-name">
👤 <?php echo htmlspecialchars($user['name']); ?>
</span>

<a href="userlogout.php" class="btn btn-danger btn-sm ms-2">
Logout
</a>

<?php } ?>

</div>
</div>
</nav>

<!-- CONTENT -->
<div class="container my-5">

<div class="page-title">
<h2>🚗 My Bookings</h2>

<p>
Welcome,
<b><?php echo htmlspecialchars($user['name']); ?></b>
</p>
</div>

<div class="row">

<?php
if(mysqli_num_rows($result) > 0){

while($row = mysqli_fetch_assoc($result)){

$whatsapp_number = "919109085923";

$message = rawurlencode(
"Hello RentRide,

My booking has been confirmed.

Booking Details:
Vehicle: ".$row['brand']." ".$row['name']."
Pickup Date: ".date("d M Y", strtotime($row['pickup_date']))."
Return Date: ".date("d M Y", strtotime($row['return_date']))."
Total Amount: ₹".$row['total_price']."

I would like to proceed with the payment."
);

?>

<div class="col-lg-4 col-md-6 mb-4">

<div class="card booking-card">

<img src="uploads/<?php echo $row['image']; ?>"
onerror="this.src='https://via.placeholder.com/600x400?text=No+Image';">

<div class="card-body">

<h5 class="vehicle-title">
<?php echo htmlspecialchars($row['brand']." ".$row['name']); ?>
</h5>

<p class="location">
📍 <?php echo htmlspecialchars($row['city']); ?>
</p>

<hr>

<p><b>Pickup:</b>
<?php echo date("d M Y", strtotime($row['pickup_date'])); ?>
</p>

<p><b>Return:</b>
<?php echo date("d M Y", strtotime($row['return_date'])); ?>
</p>

<p><b>Location:</b>
<?php echo htmlspecialchars($row['location']); ?>
</p>

<p><b>Total Days:</b>
<?php echo $row['days']; ?>
</p>

<h5 class="text-success mb-3">
₹<?php echo $row['total_price']; ?>
</h5>

<?php
$status = strtolower(trim($row['status']));
?>

<!-- STATUS -->

<?php if($status == 'pending'){ ?>

<span class="badge bg-warning text-dark">
Pending
</span>

<?php } elseif($status == 'confirmed'){ ?>

<span class="badge bg-success">
Approved
</span>

<?php } else { ?>

<span class="badge bg-danger">
Cancelled
</span>

<?php } ?>


<!-- WHATSAPP BUTTON ALWAYS SHOW -->

<div class="mt-3">

<?php
$whatsapp_number = "919109085923"; // Your WhatsApp Number

$message = "Hello, I want to discuss my booking.%0A%0A".
"Customer Name: ".$user['name']."%0A".
"Mobile: ".$user['mobile']."%0A%0A".
"Booking ID: ".$row['id']."%0A".
"Vehicle: ".$row['brand']." ".$row['name']."%0A".
"Pickup Date: ".$row['pickup_date']."%0A".
"Return Date: ".$row['return_date']."%0A".
"Location: ".$row['location']."%0A".
"Days: ".$row['days']."%0A".
"Total Price: ₹".$row['total_price'];
?>

<a href="https://wa.me/<?php echo $whatsapp_number; ?>?text=<?php echo $message; ?>"
target="_blank"
class="btn btn-success w-100">

💬 Contact on WhatsApp

</a>

</div>

</div>
</div>
</div>

<?php
}
}else{
?>

<div class="col-12">

<div class="empty-box">

<h3>No Bookings Yet 😢</h3>

<p class="mb-4">
Go and book your first ride now 🚗
</p>

<a href="index.php" class="btn btn-primary">
Browse Vehicles
</a>

</div>

</div>

<?php } ?>

</div>
</div>

<!-- FOOTER -->
<footer>

<div class="container text-center">

<hr style="border-color:#444;">

<p>
© 2026 RentRide | All Rights Reserved | Designed By Pushpendra
</p>

</div>

</footer>

</body>
</html>