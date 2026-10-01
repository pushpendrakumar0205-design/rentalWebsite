<?php
include "config.php";
session_start();

if (!isset($_SESSION['admin'])) {
  header("Location:adminlogin.php");
  exit();
}

// EDIT FETCH
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM vehicles WHERE id=$id"));
}

// ADD
if (isset($_POST['add'])) {

  $city = $_POST['city'];
  $name = $_POST['name'];
  $brand = $_POST['brand'];
  $engine = $_POST['engine'];
  $mileage = $_POST['mileage'];
  $fuel = $_POST['fuel'];
  $price = $_POST['price'];

  if ($city == "" || $name == "" || $brand == "" || $price == "") {
    echo "<script>alert('All fields required');</script>";
  } else {

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    if (!is_dir("uploads")) {
      mkdir("uploads");
    }

    move_uploaded_file($tmp, "uploads/" . $image);

    mysqli_query($conn, "INSERT INTO vehicles 
    (city,name,brand,engine,mileage,fuel,price,image)
    VALUES('$city','$name','$brand','$engine','$mileage','$fuel','$price','$image')");

    header("Location:vehicle_admin.php");
  }
}

// DELETE
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM vehicles WHERE id=$id");
  header("Location:vehicle_admin.php");
}

// UPDATE
if (isset($_POST['update'])) {

  $id = $_POST['id'];
  $city = $_POST['city'];
  $name = $_POST['name'];
  $brand = $_POST['brand'];
  $engine = $_POST['engine'];
  $mileage = $_POST['mileage'];
  $fuel = $_POST['fuel'];
  $price = $_POST['price'];

  $image = $_FILES['image']['name'];
  $tmp = $_FILES['image']['tmp_name'];

  if ($image != "") {

    move_uploaded_file($tmp, "uploads/" . $image);

    mysqli_query($conn, "UPDATE vehicles SET 
    city='$city',name='$name',brand='$brand',
    engine='$engine',mileage='$mileage',fuel='$fuel',
    price='$price',image='$image'
    WHERE id=$id");
  } else {

    mysqli_query($conn, "UPDATE vehicles SET 
    city='$city',name='$name',brand='$brand',
    engine='$engine',mileage='$mileage',fuel='$fuel',
    price='$price'
    WHERE id=$id");
  }

  header("Location:vehicle_admin.php");
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Vehicle Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #f1f2f6;
    }

    .sidebar {
      height: 100vh;
      background: #212529;
      color: white;
      position: fixed;
      width: 220px;
    }

    .sidebar a {
      display: block;
      color: white;
      padding: 12px;
      text-decoration: none;
    }

    .sidebar a:hover {
      background: #343a40;
    }

    .content {
      margin-left: 220px;
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4 class="text-center p-3">Admin Panel</h4>
    <a href="index.php">🏠 Home</a>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="vehicle_admin.php">🚗 Vehicles</a>
    <a href="admin_city.php">🏙 City</a>
    <a href="admin_brand.php">🏷 Brand</a>
    <a href="adminlogout.php">🚪 Logout</a>
  </div>

  <div class="content">

    <div class="container mt-4">

      <div class="row">

        <!-- FORM -->
        <div class="col-md-4">
          <div class="card p-3 shadow">

            <h5><?php echo isset($edit) ? "Update Vehicle" : "Add Vehicle"; ?></h5>

            <form method="POST" enctype="multipart/form-data">

              <input type="hidden" name="id" value="<?php if (isset($edit)) echo $edit['id']; ?>">

              <!-- City -->
              <select name="city" class="form-control mb-2">
                <option value="">Select City</option>
                <?php
                $res = mysqli_query($conn, "SELECT * FROM city_master");
                while ($c = mysqli_fetch_assoc($res)) {
                ?>
                  <option value="<?php echo $c['id']; ?>"
                    <?php if (isset($edit) && $edit['city'] == $c['id']) echo "selected"; ?>>
                    <?php echo $c['city']; ?>
                  </option>
                <?php } ?>
              </select>

              <!-- Brand -->
              <select name="brand" class="form-control mb-2">
                <option value="">Select Brand</option>
                <?php
                $res = mysqli_query($conn, "SELECT * FROM brand_master");
                while ($b = mysqli_fetch_assoc($res)) {
                ?>
                  <option value="<?php echo $b['id']; ?>"
                    <?php if (isset($edit) && $edit['brand'] == $b['id']) echo "selected"; ?>>
                    <?php echo $b['brand']; ?>
                  </option>
                <?php } ?>
              </select>

              <input type="text" name="name" placeholder="Vehicle Name" class="form-control mb-2"
                value="<?php if (isset($edit)) echo $edit['name']; ?>">

              <!-- Engine -->
              <select name="engine" class="form-control mb-2">
                <option value="">Select Engine</option>
                <?php
                $engines = ["100cc", "125cc", "150cc", "200cc", "350cc", "400cc", "450cc", "650cc"];
                foreach ($engines as $e) {
                ?>
                  <option value="<?php echo $e; ?>"
                    <?php if (isset($edit) && $edit['engine'] == $e) echo "selected"; ?>>
                    <?php echo $e; ?>
                  </option>
                <?php } ?>
              </select>

              <input type="text" name="mileage" placeholder="Mileage (e.g 40 km/l)" class="form-control mb-2"
                value="<?php if (isset($edit)) echo $edit['mileage']; ?>">

              <!-- Fuel -->
              <select name="fuel" class="form-control mb-2">
                <option value="">Select Fuel</option>
                <option value="Petrol">Petrol</option>
                <option value="Diesel">Diesel</option>
                <option value="Electric">Electric</option>
              </select>

              <input type="number" name="price" placeholder="Price" class="form-control mb-2"
                value="<?php if (isset($edit)) echo $edit['price']; ?>">

              <input type="file" name="image" class="form-control mb-2">

              <?php if (isset($edit)) { ?>
                <button name="update" class="btn btn-success w-100">Update</button>
              <?php } else { ?>
                <button name="add" class="btn btn-dark w-100">Add Vehicle</button>
              <?php } ?>

            </form>

          </div>
        </div>

        <!-- TABLE -->
        <div class="col-md-8">
          <div class="card p-3 shadow">

            <h5>All Vehicles</h5>

            <table class="table table-bordered mt-3">

              <tr class="table-dark">
                <th>#</th>
                <th>City</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Engine</th>
                <th>Mileage</th>
                <th>Fuel</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
              </tr>

              <?php
              $q = mysqli_query($conn, "
SELECT V.*, C.city, B.brand 
FROM vehicles V
LEFT JOIN city_master C ON V.city = C.id
LEFT JOIN brand_master B ON V.brand = B.id
");

              $i = 1;
              while ($row = mysqli_fetch_assoc($q)) {
              ?>

                <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $row['city']; ?></td>
                  <td><?php echo $row['name']; ?></td>
                  <td><?php echo $row['brand']; ?></td>
                  <td><?php echo $row['engine']; ?></td>
                  <td><?php echo $row['mileage']; ?></td>
                  <td><?php echo $row['fuel']; ?></td>
                  <td>₹<?php echo $row['price']; ?></td>

                  <td><img src="uploads/<?php echo $row['image']; ?>" width="70"></td>

                  <td>
                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>

                    <a href="?delete=<?php echo $row['id']; ?>"
                      onclick="return confirm('Delete?')"
                      class="btn btn-danger btn-sm">Delete</a>
                  </td>
                </tr>

              <?php } ?>

            </table>

          </div>
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