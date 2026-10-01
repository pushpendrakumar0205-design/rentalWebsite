<?php

include "config.php";

if (isset($_POST['signup'])) {

    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (name, mobile, email, password)
        VALUES ('$name','$mobile', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        echo "Signup Successful";
        header("Location:login.html");
    } else {
        echo "Error";
    }
}
