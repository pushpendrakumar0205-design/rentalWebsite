<?php
include "config.php";

session_start();

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded admin
    if ($username == "admin" && $password == "1234") {

        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
    } else {
        echo "Invalid Login";
    }
}
?>

<!-- <!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5" style="max-width:400px;">
<h3>Admin Login</h3>

<form method="POST">
<input type="text" name="username" class="form-control mb-2" placeholder="Username">
<input type="password" name="password" class="form-control mb-2" placeholder="Password">

<button name="login" class="btn btn-dark w-100">Login</button>
</form>

</div>

</body>
</html>  -->



<?php
// session_start();

// if(isset($_POST['login'])){

//   $username = $_POST['username'];
//   $password = $_POST['password'];

//   if($username == "admin" && $password == "1234"){
//     $_SESSION['admin'] = true;
//     header("Location: admin.php");
//     exit();
//   } else {
//     $error = "Invalid Username or Password!";
//   }
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .login-card h3 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-dark {
            border-radius: 8px;
            padding: 10px;
            font-weight: 600;
        }

        .logo {
            text-align: center;
            font-size: 30px;
            margin-bottom: 10px;
        }

        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <div class="logo">🔐</div>

        <h3>Admin Login</h3>

        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <form method="POST">

            <input type="text" name="username" class="form-control mb-3" placeholder="Enter Username" required>

            <input type="password" name="password" class="form-control mb-3" placeholder="Enter Password" required>


            <button type="submit" class="btn btn-dark w-100" name="login">Login</button>

        </form>

    </div>

</body>

</html>