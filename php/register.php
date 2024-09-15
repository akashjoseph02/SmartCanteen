<?php
include('config.php');

if (isset($_POST["submit"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $reg_no = $_POST['reg_no'];

    $sql = "INSERT INTO users (email, password, name, reg_no) VALUES ('$email', '$hashed_password', '$name', '$reg_no')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration Successful!'); location.href='../login.html';</script>";
    } else {
        echo "<script>alert('Registration failed: " . $conn->error . "'); location.href='../signup.html';</script>";
    }
}
$conn->close();
?>
