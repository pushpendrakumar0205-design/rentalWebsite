<?php
include "config.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicles</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card img {
            height: 180px;
            object-fit: cover;
        }
    </style>

</head>

<body>

    <div class="container my-5">
        <h2>Available Vehicles</h2>

        <div class="row">
            <?php
            $sql = "select * from vehicles";
            $res = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($res)) {
            ?>




                <div class="col-md-4">
                    <div class="card">
                        <img src="<?php echo $row['image']; ?>">
                        <div class="card-body">
                            <h5><?php echo $row['name']; ?></h5>
                            <p><?php echo $row['price']; ?>/day</p>
                            <a href="details.html" class="btn btn-dark">View</a>
                        </div>
                    </div>
                </div>
            <?php } ?>


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