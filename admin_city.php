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
  $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM city_master WHERE id=$id"));
}

// ADD
if (isset($_POST['add'])) {
  $city = $_POST['city'];

  if ($city == "") {
    echo "<script>alert('City is required');</script>";
  } else {
    mysqli_query($conn, "INSERT INTO city_master (city) VALUES('$city')");
    header("Location:admin_city.php");
    exit();
  }
}

// DELETE
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM city_master WHERE id=$id");
  header("Location:admin_city.php");
  exit();
}

// UPDATE
if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $city = $_POST['city'];

  if ($city == "") {
    echo "<script>alert('City is required');</script>";
  } else {
    mysqli_query($conn, "UPDATE city_master SET city='$city' WHERE id=$id");
    header("Location:admin_city.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>City Master</title>

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

    .content {
      margin-left: 220px;
    }

    .navbar {
      background: #212529;
    }

    .card {
      border-radius: 10px;
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
      <span class="navbar-brand">City Management</span>
    </nav>

    <div class="container mt-4">
      <div class="row">

        <!-- FORM -->
        <div class="col-md-4">
          <div class="card p-3 shadow">

            <h5>
              <?php if (isset($edit)) echo "Update City";
              else echo "Add City"; ?>
            </h5>

            <form method="POST">

              <input type="hidden" name="id"
                value="<?php if (isset($edit)) echo $edit['id']; ?>">

              <input type="text" name="city" placeholder="Enter City"
                class="form-control mb-3"
                value="<?php if (isset($edit)) echo $edit['city']; ?>">

              <?php if (isset($edit)) { ?>
                <button name="update" class="btn btn-success w-100">Update</button>
              <?php } else { ?>
                <button name="add" class="btn btn-dark w-100">Add City</button>
              <?php } ?>

            </form>

          </div>
        </div>

        <!-- TABLE -->
        <div class="col-md-8">
          <div class="card p-3 shadow">

            <h5>All Cities</h5>

            <table class="table table-bordered mt-3">

              <tr class="table-dark">
                <th>#</th>
                <th>City Name</th>
                <th>Action</th>
              </tr>

              <?php
              $result = mysqli_query($conn, "SELECT * FROM city_master");
              $i = 1;

              while ($row = mysqli_fetch_assoc($result)) {
              ?>

                <tr>
                  <td><?php echo $i++; ?></td>

                  <td><?php echo $row['city']; ?></td>

                  <td>
                    <a href="?edit=<?php echo $row['id']; ?>"
                      class="btn btn-warning btn-sm">Edit</a>

                    <a href="?delete=<?php echo $row['id']; ?>"
                      onclick="return confirm('Delete this city?')"
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