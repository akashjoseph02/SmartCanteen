<?php
session_start(); // Always at the very top

// Your PHP code here (e.g., database connection, fetch user details)
include('php/config.php');

// Fetch the logged-in user's details (like name, email) using the session
$email = $_SESSION['email'];
$sql = "SELECT name FROM users WHERE email='$email'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$username = $row['name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Feedback Form</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #c9e3e5, #a8dadc);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            max-width: 400px;
            /* Reduced width */
            width: 100%;
            position: relative;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.25);
        }

        h2 {
            text-align: center;
            color: #1059cf;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            color: #4e4e4e;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="email"],
        textarea,
        select {
            width: 95%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        select#restaurant {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            color: #010610;
            background-color: #f1f9fb;
            border: 2px solid #12739d;
            transition: border 0.3s ease-in-out;
            width: 101%;
        }

        select#rating {
            width: 101%;
        }

        select#restaurant:focus {
            outline: none;
            border: 2px solid #03adba;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #12739d;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #03adba;
        }

        .character-count {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
            text-align: right;
        }

        /* Popup styling */
        .popup {
            position: fixed;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 320px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            z-index: 1000;
            transition: top 0.5s ease, opacity 0.3s ease;
            opacity: 0;
        }

        .popup.show {
            top: 20px;
            opacity: 1;
        }

        .popup h4 {
            color: #2857d8;
            margin-bottom: 15px;
            font-size: 20px;
            text-align: center;
        }

        .popup p {
            font-size: 16px;
            color: #333;
            text-align: center;
        }

        .close-popup {
            display: block;
            margin: 15px auto 0;
            padding: 10px 20px;
            background-color: #43e9ff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .close-popup:hover {
            background-color: #22f8ff;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            h2 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Restaurant Feedback Form</h2>
        <form id="feedbackForm" action="process_feedback.php" method="POST">
            <div class="form-group">
                <label for="restaurant">Select Restaurant:</label>
                <select id="restaurant" name="restaurant" required>
                    <option value="">Choose Restaurant</option>
                    <option value="Surf and Turf">Surf and Turf</option>
                    <option value="Cafe Mingos">Cafe Mingos</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Your Name:</label>
                <!-- <input type="text" id="name" name="name" required> -->
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($username); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <!-- <input type="email" id="email" name="email" required> -->
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="rating">Rate our Service:</label>
                <select id="rating" name="rating" required>
                    <option value="">Select a rating</option>
                    <option value="5">Excellent (5)</option>
                    <option value="4">Very Good (4)</option>
                    <option value="3">Good (3)</option>
                    <option value="2">Fair (2)</option>
                    <option value="1">Poor (1)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="comments">Additional Comments:</label>
                <textarea id="comments" name="comments" rows="4" placeholder="Your comments..."
                    maxlength="200"></textarea>
                <div class="character-count" id="charCount">0/200</div>
            </div>
            <input type="submit" value="Submit Feedback">
        </form>
    </div>

    <!-- Pop-up window for recommendations -->
    <div class="popup" id="popup">
        <p id="popupMessage"></p>
        <button class="close-popup" onclick="closePopup()">Close</button>
    </div>

    <script>
        const feedbackForm = document.getElementById('feedbackForm');
        const comments = document.getElementById('comments');
        const charCount = document.getElementById('charCount');

        comments.addEventListener('input', function() {
            charCount.textContent = `${comments.value.length}/200`;
        });

        feedbackForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission

            const name = document.getElementById('name').value;
            const rating = document.getElementById('rating').value;
            const commentsValue = comments.value.trim();
            let recommendation = '';

            // Sentiment Analysis using a keyword approach
            const positiveWords = ['great', 'excellent', 'awesome', 'fantastic', 'good', 'delicious', 'amazing', 'perfect', 'wonderful'];
            const negativeWords = ['bad', 'poor', 'terrible', 'horrible', 'awful', 'disgusting', 'unhappy', 'disappointed'];

            let positiveScore = 0;
            let negativeScore = 0;

            const lowerComments = commentsValue.toLowerCase();

            positiveWords.forEach(word => {
                if (lowerComments.includes(word)) positiveScore++;
            });

            negativeWords.forEach(word => {
                if (lowerComments.includes(word)) negativeScore++;
            });

            // Pop-up message logic based on rating and comments
            if (rating === '5') {
                recommendation = `Thank you, ${name}, for the excellent feedback! We're glad you had a great experience!`;
            } else if (rating === '4') {
                recommendation = `Thank you, ${name}, for the positive feedback! We'll strive to make it even better!`;
            } else if (rating === '3') {
                recommendation = `Thank you, ${name}. We appreciate your feedback and will work on improving our service.`;
            } else if (rating === '2' || rating === '1') {
                recommendation = `We're sorry, ${name}, that your experience wasn't satisfactory. We value your feedback and will work on our weaknesses.`;
            }

            // Adjusting the message further based on the sentiment of comments
            if (positiveScore > negativeScore) {
                recommendation += ` Your comments highlight what we're doing well, and we appreciate that!`;
            } else if (negativeScore > positiveScore) {
                recommendation += ` Your comments indicate areas for improvement, and we take that seriously.`;
            }

            // Display the recommendation in the popup
            document.getElementById('popupMessage').innerText = recommendation;
            showPopup();

            // Reset the form after feedback submission
            feedbackForm.reset();
            charCount.textContent = '0/200';
        });


        function showPopup() {
            const popup = document.getElementById('popup');
            popup.classList.add('show');
            setTimeout(closePopup, 4000); // Auto close after 4 seconds
        }

        function closePopup() {
            const popup = document.getElementById('popup');
            popup.classList.remove('show');
            // Redirect to MainPage.html
            window.location.href = 'MainPage.php';
        }
    </script>
</body>

</html>