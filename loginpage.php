<?php

if(isset($_GET['vehicle_id'])){
$vehicle_id = $_GET['vehicle_id'];

}else{
  $vehicle_id = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      height: 100vh;
      background: linear-gradient(135deg, #667eea, #764ba2);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    /* Login Box */
    .login-box {
      width: 100%;
      max-width: 380px;
      padding: 30px;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      color: white;
    }

    /* Input */
    .form-control {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      color: white;
    }

    .form-control::placeholder {
      color: #ddd;
    }

    .form-control:focus {
      box-shadow: none;
      background: rgba(255, 255, 255, 0.3);
      color: white;
    }

    /* Button */
    .btn-login {
      background: #fff;
      color: #333;
      font-weight: bold;
    }

    .btn-login:hover {
      background: #ddd;
    }

    /* Link */
    a {
      color: #fff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>

</head>

<body>

  <div class="login-box shadow-lg">

    <h3 class="text-center mb-4">🔐 Login</h3>

    <form action="login.php" method="POST">

      <input type="email" name="email" placeholder="Enter Email" class="form-control mb-3" required>

      <input type="password" name="password" placeholder="Enter Password" class="form-control mb-3" required>
      <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id ?>">
    
      <button type="submit"  class="btn btn-login w-100" name="login">Login</button>

    </form>

    <p class="text-center mt-3">
      Don't have account? <a href="signup.html">Signup</a>
    </p>

  </div>

</body>

</html>