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
  $price = $_POST['price'];

  if ($city == "" || $name == "" || $brand == "" || $price == "") {
    echo "<script>alert('All fields are required');</script>";
  } else {

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp, "uploads/" . $image);

    mysqli_query($conn, "INSERT INTO vehicles (city,name,brand,price,image)
    VALUES('$city','$name','$brand','$price','$image')");

    header("Location:admin.php");
  }
}

// DELETE
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM vehicles WHERE id=$id");
  header("Location:admin.php");
}

// UPDATE
if (isset($_POST['update'])) {
  $city = $_POST['city'];
  $id = $_POST['id'];
  $name = $_POST['name'];
  $brand = $_POST['brand'];
  $price = $_POST['price'];

  if ($city == "" || $name == "" || $brand == "" || $price == "") {
    echo "<script>alert('All fields are required');</script>";
  } else {

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    if ($image != "") {
      move_uploaded_file($tmp, "uploads/" . $image);

      mysqli_query($conn, "UPDATE vehicles SET 
      city = '$city',name='$name',brand='$brand',price='$price',image='$image' WHERE id=$id");
    } else {
      mysqli_query($conn, "UPDATE vehicles SET 
      name='$name',brand='$brand',price='$price' WHERE id=$id");
    }

    header("Location:admin.php");
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Admin Panel</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #f1f2f6;
    }

    /* Sidebar */
    .sidebar {
      height: 100vh;
      background: #212529;
      color: white;
      position: fixed;
      width: 220px;
    }

    .sidebar h4 {
      text-align: center;
      padding: 15px;
      border-bottom: 1px solid #444;
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

    /* Content */
    .content {
      margin-left: 220px;
    }

    /* Navbar */
    .navbar {
      background: #212529;
    }

    /* Card */
    .card {
      border: none;
      border-radius: 10px;
    }

    /* Table */
    .table {
      background: white;
    }
  </style>

</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4>Admin Panel</h4>
    <a href="adminlogout.php">🏠 Home</a>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="vehicle_admin.php">🚗 Vehicles</a>
    <a href="admin_city.php">🏙 City</a>
    <a href="admin_brand.php">🏷️ brand </a>
    <a href="adminlogout.php">🚪 Logout</a>
  </div>

  <!-- Content -->
  <div class="content">

    <!-- Navbar -->
    <nav class="navbar navbar-dark px-3">
      <span class="navbar-brand">Vehicle Management System</span>
    </nav>

    <div class="container mt-4">

      <div class="row">

        <!-- Form -->
        <div class="col-md-4">
          <div class="card p-3 shadow">

            <h5><?php if (isset($edit)) echo "Update Vehicle";
                else echo "Add Vehicle"; ?></h5>

            <form method="POST" enctype="multipart/form-data">

              <input type="hidden" name="id" value="<?php if (isset($edit)) echo $edit['id']; ?>">

              <input type="text" name="city" placeholder="city" required
                class="form-control mb-2"
                value="<?php if (isset($edit)) echo $edit['city']; ?>">

              <input type="text" name="name" placeholder="Vehicle Name" required
                class="form-control mb-2"
                value="<?php if (isset($edit)) echo $edit['name']; ?>">

              <input type="text" name="brand" placeholder="Brand" required
                class="form-control mb-2"
                value="<?php if (isset($edit)) echo $edit['brand']; ?>">

              <input type="number" name="price" placeholder="Price" required
                class="form-control mb-2"
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

        <!-- Table -->
        <div class="col-md-8">
          <div class="card p-3 shadow">

            <h5>All Vehicles</h5>

            <table class="table table-bordered mt-3">

              <tr class="table-dark">
                <th>#</th>
                <th>City</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
              </tr>

              <?php
              $result = mysqli_query($conn, "SELECT * FROM vehicles");
              $i = 1;
              while ($row = mysqli_fetch_assoc($result)) {
              ?>

                <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $row['city']; ?></td>
                  <td><?php echo $row['name']; ?></td>
                  <td><?php echo $row['brand']; ?></td>
                  <td>₹<?php echo $row['price']; ?></td>

                  <td>
                    <img src="uploads/<?php echo $row['image']; ?>" width="70" class="rounded">
                  </td>

                  <td>
                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>

                    <a href="?delete=<?php echo $row['id']; ?>"
                      onclick="return confirm('Are you Sure you want to delete')"
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