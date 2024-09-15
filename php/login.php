<?php
session_start(); // Start the session
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize user input to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);

    // Query to find the user
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Store the user's email and user_id in session variables
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $row['user_id']; // Assuming 'user_id' is the primary key in 'users' table

            // Redirect to MainPage.php
            header("Location: ../MainPage.php");
            exit();
        } else {
            echo "<script>alert('Invalid Password!'); location.href='../login.html';</script>";
        }
    } else {
        echo "<script>alert('No User Found!'); location.href='../login.html';</script>";
    }
}
?>
