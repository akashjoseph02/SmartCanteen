<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database connection
    include("config.php");

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo "<script>
                    alert('Login Successful!');
                    // Redirect to dashboard or home page
                    window.location.href = 'dashboard.html';
                  </script>";
        } else {
            echo "<script>
                    alert('Invalid Password!');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Invalid Email!');
                window.history.back();
              </script>";
    }

    $conn->close();
}
?>


