<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        /* Reset and basic styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #1E90FF; /* Changed to a more specific blue */
            font-size: 2em;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 20px;
        }
        a.home-link {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 1em;
            color: #1E90FF;
            text-decoration: none;
            border: 2px solid #1E90FF;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        a.home-link:hover {
            background-color: #1E90FF;
            color: #fff;
        }
    </style>
    <script>
        // JavaScript to redirect to feedback form after 3 seconds
        setTimeout(function() {
            window.location.href = 'recommendation.php';
        }, 1500);
    </script>
</head>
<body>
    <a href="MainPage.php" class="home-link">Home</a>
    <div class="container">
        <h1>Order Placed Successfully!</h1>
        <p>Thank you for your order. We will process it soon...!</p>
        <p>You will be redirected to the feedback page shortly.</p>
    </div>
</body>
</html>
