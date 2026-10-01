<?php
include "config.php";

$id = $_GET['id'];

$sql = "SELECT V.*, C.city, B.brand 
        FROM vehicles V
        LEFT JOIN city_master C ON V.city = C.id
        LEFT JOIN brand_master B ON V.brand = B.id
        WHERE V.id = '$id'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vehicle Details</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { font-family: Arial; }

.vehicle-img {
  width: 100%;
  height: 350px;
  object-fit: cover;
  border-radius: 10px;
}

.thumbnail img {
  height: 80px;
  cursor: pointer;
}

.spec-box {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 10px;
}
</style>

</head>
<body>

<div class="container my-5">

  <div class="row">

    <!-- Left: Images -->
    <div class="col-md-6">
      <img id="mainImage" src="uploads/<?php echo $row['image']; ?>" class="vehicle-img mb-3">

      <div class="d-flex gap-2 thumbnail">
        <img src="uploads/<?php echo $row['image']; ?>" onclick="changeImg(this)">
        <img src="uploads/sample1.jpg" onclick="changeImg(this)">
        <img src="uploads/sample2.jpg" onclick="changeImg(this)">
      </div>
    </div>

    <!-- Right: Details -->
    <div class="col-md-6">

      <h2><?php echo $row['brand']; ?> - <?php echo $row['name']; ?></h2>

      <h4 class="text-success">₹<?php echo $row['price']; ?> / day</h4>

      <p><?php echo $row['description']; ?></p>

      <div class="spec-box mb-3">
        <p><b>Engine:</b> <?php echo $row['engine']; ?></p>
        <p><b>Mileage:</b> <?php echo $row['mileage']; ?></p>
        <p><b>Fuel:</b> <?php echo $row['fuel']; ?></p>
        <p><b>City:</b> <?php echo $row['city']; ?></p>
      </div>

      <!-- BOOK BUTTON -->
      <a href="booking.php?id=<?php echo $row['id']; ?>" 
         class="btn btn-dark btn-lg w-100">
         Book Now
      </a>

    </div>

  </div>

</div>

<script>
function changeImg(e){
  document.getElementById("mainImage").src = e.src;
}
</script>

</body>
</html>