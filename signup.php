<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eventify";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];


    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);


        if ($stmt->execute()) {
            echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Event Created</title>
                <link rel="stylesheet" href="css/orat.css">
            </head>
            <body>
                <header class="header">
                    <a href="#" class="logo">
                        <img src="images/eventify.png" alt="Eventify Logo" class="logo-img">
                        <span class="logo-text">eventify</span>
                    </a>
                </header>
                <section class="success-message">
                    <h3>Account created successfully!</h3>
                    <p>Create your event:</p><br>
                    <form action="organizer.html" method="POST">
                        <button type="submit" class="btn">create event</button>
                    </form>
                </section>
            </body>
            </html>
            ';
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

$conn->close();
?>
