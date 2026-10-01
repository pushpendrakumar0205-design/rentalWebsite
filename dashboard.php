<?php
include "config.php";
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location:adminlogin.php");
    exit();
}

date_default_timezone_set("Asia/Kolkata");

// ================= FILTER =================
$today = date("Y-m-d");

// DEFAULT = TODAY
$where = "1";
$where_booking = "1";

// DATE RANGE (highest priority)
if (!empty($_GET['start']) && !empty($_GET['end'])) {

    $start = $_GET['start'];
    $end = $_GET['end'];

    $where = "DATE(created_at) BETWEEN '$start' AND '$end'";
    $where_booking = "DATE(B.created_at) BETWEEN '$start' AND '$end'";
}

// TOTAL
elseif (isset($_GET['filter']) && $_GET['filter'] == "total") {

    $where = "1";
    $where_booking = "1";
}

// TODAY
elseif (isset($_GET['filter']) && $_GET['filter'] == "today") {

    $where = "DATE(created_at)='$today'";
    $where_booking = "DATE(B.created_at)='$today'";
}

// ================= STATUS FILTER (ONLY ONCE) =================
if (!empty($_GET['status_filter'])) {
    $status = $_GET['status_filter'];

    $where .= " AND status='$status'";
    $where_booking .= " AND B.status='$status'";
}

// ================= BOOKING FILTER =================
$booking_where = $where_booking;

// NAME
if (!empty($_GET['name'])) {
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $booking_where .= " AND U.name LIKE '%$name%'";
}

// MOBILE
if (!empty($_GET['mobile'])) {
    $mobile = mysqli_real_escape_string($conn, $_GET['mobile']);
    $booking_where .= " AND U.mobile LIKE '%$mobile%'";
}

// VEHICLE
if (!empty($_GET['vehicle'])) {
    $vehicle = mysqli_real_escape_string($conn, $_GET['vehicle']);
    $booking_where .= " AND V.name LIKE '%$vehicle%'";
}

// CITY
if (!empty($_GET['city'])) {
    $booking_where .= " AND C.id='{$_GET['city']}'";
}

// PICKUP DATE
if (!empty($_GET['pickup'])) {
    $booking_where .= " AND B.pickup_date='{$_GET['pickup']}'";
}

// RETURN DATE
if (!empty($_GET['return'])) {
    $booking_where .= " AND B.return_date='{$_GET['return']}'";
}

// LOCATION
if (!empty($_GET['location'])) {
    $location = mysqli_real_escape_string($conn, $_GET['location']);
    $booking_where .= " AND B.location LIKE '%$location%'";
}

// CREATED DATE
if (!empty($_GET['created'])) {
    $booking_where .= " AND DATE(B.created_at)='{$_GET['created']}'";
}
// ================= STATUS =================
if (isset($_GET['status'])) {
    mysqli_query($conn, "UPDATE bookings SET status='{$_GET['status']}' WHERE id='{$_GET['id']}'");
    header("Location:dashboard.php");
}

// ================= DELETE =================
if (isset($_GET['delete'])) {
    mysqli_query($conn, "DELETE FROM bookings WHERE id='{$_GET['delete']}'");
    header("Location:dashboard.php");
}


/* ===== VEHICLE BOOKING CHART ===== */
$vehicleChart = mysqli_query($conn, "
SELECT V.name as vehicle_name, COUNT(B.id) as total
FROM bookings B
LEFT JOIN vehicles V ON B.vehicle_id = V.id
WHERE $where_booking
GROUP BY V.id
");

$vehicleNames = [];
$vehicleCounts = [];

while ($row = mysqli_fetch_assoc($vehicleChart)) {
    $vehicleNames[] = $row['vehicle_name'];
    $vehicleCounts[] = $row['total'];
}

/* ===== MONTHLY REVENUE CHART ===== */
$chartData = mysqli_query($conn, "
SELECT 
MONTH(created_at) as month,
SUM(CASE WHEN status='Confirmed' THEN total_price ELSE 0 END) as confirmed,
SUM(total_price) as estimated
FROM bookings
WHERE $where
GROUP BY MONTH(created_at)
");

$months = [];
$confirmedData = [];
$estimatedData = [];

while ($row = mysqli_fetch_assoc($chartData)) {
    $months[] = date("M", mktime(0, 0, 0, $row['month'], 1));
    $confirmedData[] = $row['confirmed'];
    $estimatedData[] = $row['estimated'];
}

// ================= STATS =================
$today = date("Y-m-d");

$todayDate = date("Y-m-d");

$todayBooking = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM bookings WHERE DATE(created_at)='$todayDate'"
));
$totalBooking = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM bookings"
));

// TODAY CONFIRMED
$todayDate = date("Y-m-d");

$todayBooking = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT id FROM bookings WHERE DATE(created_at)='$todayDate'"
));

$todayConfirmed = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT SUM(total_price) as t FROM bookings 
WHERE DATE(created_at)='$todayDate' AND status='Confirmed'"
))['t'] ?? 0;

$todayEstimated = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT SUM(total_price) as t FROM bookings 
WHERE DATE(created_at)='$todayDate'"
))['t'] ?? 0;

// TOTAL CONFIRMED
$totalConfirmed = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT SUM(total_price) as t FROM bookings 
WHERE status='Confirmed'"
))['t'] ?? 0;

$filteredRevenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as t FROM bookings WHERE $where"))['t'] ?? 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: #f4f6f9;
            font-family: sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            height: 100vh;
            width: 220px;
            position: fixed;
            background: #212529;
            color: white;
            transition: 0.3s;
        }

        .sidebar.hide {
            left: -220px;
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

        /* CONTENT */
        .content {
            margin-left: 220px;
            padding: 20px;
            transition: 0.3s;
        }

        .mobile-nav {
            display: none;
            background: #212529;
            color: white;
            padding: 10px;
        }

        @media(max-width:768px) {
            .sidebar {
                left: -220px;
            }

            .content {
                margin-left: 0;
            }

            .mobile-nav {
                display: block;
            }
        }

        /* CARDS */
        .card-box {
            padding: 15px;
            border-radius: 10px;
            color: white;
            cursor: pointer;
        }

        .bg1 {
            background: #667eea;
        }

        .bg2 {
            background: #ff416c;
        }

        .bg3 {
            background: #28a745;
        }

        .bg4 {
            background: #6a82fb;
        }

        canvas {
            max-height: 180px !important;
        }
    </style>
</head>

<body>

    <!-- MOBILE NAV -->
    <div class="mobile-nav">
        <span onclick="toggleSidebar()">☰</span> Dashboard
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <h4 class="text-center mt-3">Admin</h4>
        <a href="index.php">🏠 Home</a>
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="vehicle_admin.php">🚗 Vehicles</a>
        <a href="admin_city.php">🏙 City</a>
        <a href="admin_brand.php">🏷 Brand</a>
        <a href="adminlogout.php">🚪 Logout</a>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <h4>📊 Dashboard</h4>

        <!-- CARDS -->
        <div class="row g-3">

            <div class="col-6 col-md-3">
                <div class="card-box bg1" onclick="window.location='dashboard.php?filter=today'">
                    <h6><?= $todayBooking ?></h6>
                    <small>Today Booking</small>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card-box bg2" onclick="window.location='dashboard.php?filter=total'">
                    <h6><?= $totalBooking ?></h6>
                    <small>Total Booking</small>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card-box bg3" onclick="window.location='dashboard.php?filter=today&status_filter=Confirmed'">
                    <h6>₹<?= $todayConfirmed ?></h6>
                    <small>Today Confirmed</small>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card-box bg4" onclick="window.location='dashboard.php?filter=today'">
                    <h6>₹<?= $todayEstimated ?></h6>
                    <small>Today Estimated</small>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card-box bg2" onclick="window.location='dashboard.php?filter=total&status_filter=Confirmed'">
                    <h6>₹<?= $totalConfirmed ?></h6>
                    <small>Total Confirmed</small>
                </div>
            </div>

        </div>
        <!-- DATE FILTER -->
        <div id="filterBox" class="card p-3 mt-3"
            style="display:<?= (isset($_GET['filter']) && $_GET['filter'] == 'total') || isset($_GET['start']) ? 'block' : 'none' ?>">
            <form method="GET">
                <label>Start Date</label>
                <input type="date" name="start" class="form-control mb-2" required>

                <label>End Date</label>
                <input type="date" name="end" class="form-control mb-2" required>

                <button class="btn btn-dark w-100">Apply Filter</button>
            </form>
        </div>

        <!-- CHART -->
        <div class="row mt-4">

            <div class="col-md-6">
                <div class="card p-3">
                    <h6>Monthly Revenue (Confirmed vs Estimated)</h6>
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-3">
                    <h6>Vehicle Wise Bookings</h6>
                    <canvas id="donutChart"></canvas>
                </div>
            </div>

        </div>

        <!-- BOOKING TABLE -->
        <div class="card mt-4 p-3">
            <h5>📋 Booking Details</h5>
            <form method="GET" class="row g-2 mb-3">

                <input type="hidden" name="start" value="<?= $_GET['start'] ?? '' ?>">
                <input type="hidden" name="end" value="<?= $_GET['end'] ?? '' ?>">

                <div class="col-md-2">
                    <label>User Name</label>
                    <input type="text" name="name" placeholder="User Name" class="form-control">
                </div>

                <div class="col-md-2">
                    <label>Mobile</label>
                    <input type="text" name="mobile" placeholder="Mobile" class="form-control">
                </div>

                <div class="col-md-2">
                    <label>Vehicle</label>
                    <input type="text" name="vehicle" placeholder="Vehicle" class="form-control">
                </div>

                <div class="col-md-2">
                    <label>City</label>
                    <select name="city" class="form-control">
                        <option value="">City</option>
                        <?php
                        $c = mysqli_query($conn, "SELECT * FROM city_master");
                        while ($r = mysqli_fetch_assoc($c)) {
                        ?>
                            <option value="<?= $r['id'] ?>"><?= $r['city'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Pickup Date</label>
                    <input type="date" name="pickup" class="form-control">
                </div>

                <div class="col-md-2">
                    <label>Return Date</label>
                    <input type="date" name="return" class="form-control">
                </div>

                <div class="col-md-2">
                    <label>Location</label>
                    <input type="text" name="location" placeholder="Location" class="form-control">
                </div>

                <div class="col-md-2">
                    <label>Created Date</label>
                    <input type="date" name="created" class="form-control">
                </div>

                <div class="col-md-2">
                    <label>Status</label>
                    <select name="status_filter" class="form-control">
                        <option value="">Status</option>
                        <option>Pending</option>
                        <option>Confirmed</option>
                        <option>Cancelled</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-dark w-100">Filter</button>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <a href="dashboard.php" class="btn btn-secondary w-100">Reset</a>
                </div>

            </form>
            <div class="table-responsive">
                <table class="table table-bordered mt-3">

                    <tr class="table-dark">
                        <th>S.N.</th>
                        <th>User Name</th>
                        <th>Mobile</th>
                        <th>Vehicle</th>
                        <th>City</th>
                        <th>Pickup Date</th>
                        <th>Return Date</th>
                        <th>Pickup Location</th>
                        <th>Total Days</th>
                        <th>Total Amount</th>
                        <th>Created Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                    <?php
                    $sql = "SELECT B.*, 
       V.name as vehicle_name, 
       C.city,
       U.name as user_name,
       U.mobile
FROM bookings B
LEFT JOIN vehicles V ON B.vehicle_id = V.id
LEFT JOIN city_master C ON V.city = C.id
LEFT JOIN users U ON B.user_id = U.id
WHERE $booking_where
ORDER BY B.id DESC";
                    $res = mysqli_query($conn, $sql);
                    $i = 1;

                    while ($row = mysqli_fetch_assoc($res)) {
                    ?>

                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $row['user_name'] ?></td>
                            <td><?= $row['mobile'] ?></td>
                            <td><?= $row['vehicle_name'] ?></td>
                            <td><?= $row['city'] ?></td>
                            <td><?= $row['pickup_date'] ?></td>
                            <td><?= $row['return_date'] ?></td>
                            <td><?= $row['location'] ?></td>
                            <td><?= $row['days'] ?></td>
                            <td>₹<?= $row['total_price'] ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <span class="badge bg-<?=
                                                        $row['status'] == "Confirmed" ? "success" : ($row['status'] == "Cancelled" ? "danger" : "warning")
                                                        ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>

                            <td>


                            <div class="action-box">

                                    <!-- CONFIRM -->
                                    <a href="?id=<?= $row['id'] ?>&status=Confirmed"
                                        class="btn btn-success btn-sm"
                                        title="Confirm Booking"
                                        onclick="return confirm('Confirm this booking?')">
                                        ✔
                                    </a>

                                    <!-- CANCEL -->
                                    <a href="?id=<?= $row['id'] ?>&status=Cancelled"
                                      class="btn btn-warning btn-sm"
                                        title="Cancel Booking"
                                        onclick="return confirm('Cancel this booking?')">
                                        ✖
                                    </a>

                                    <!-- DELETE -->
                                    <a href="?delete=<?= $row['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        title="Delete Booking"
                                        onclick="return confirm('Delete this booking permanently?')">
                                        🗑
                                    </a>

                                </div>
                              
                            </td>
                        </tr>

                    <?php } ?>

                </table>
            </div>
        </div>

        <!-- 🚗 VEHICLE TABLE (ADDED BACK) -->
        <div class="card mt-4 p-3">
            <h5>🚗 Vehicle Details</h5>

            <div class="table-responsive">
                <table class="table table-bordered mt-3">

                    <tr class="table-dark">
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>City</th>
                        <th>Price</th>
                    </tr>

                    <?php
                    $v = mysqli_query($conn, "
SELECT V.*, B.brand, C.city
FROM vehicles V
LEFT JOIN brand_master B ON V.brand = B.id
LEFT JOIN city_master C ON V.city = C.id
");

                    while ($r = mysqli_fetch_assoc($v)) {
                    ?>

                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td><img src="uploads/<?= $r['image'] ?>" width="60"></td>
                            <td><?= $r['name'] ?></td>
                            <td><?= $r['brand'] ?></td>
                            <td><?= $r['city'] ?></td>
                            <td>₹<?= $r['price'] ?></td>
                        </tr>

                    <?php } ?>

                </table>
            </div>
        </div>

    </div>

    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("hide");
        }

        // MONTHLY REVENUE CHART
        new Chart(document.getElementById("barChart"), {
            type: 'bar',
            data: {
                labels: <?= json_encode($months) ?>,
                datasets: [{
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

        // VEHICLE BOOKING CHART
        new Chart(document.getElementById("donutChart"), {
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