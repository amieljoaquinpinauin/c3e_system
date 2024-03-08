<?php
session_start(); // Start the session
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize the inputs to prevent SQL injection (you may want to improve this)
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check user credentials
    $query = "SELECT * FROM employee WHERE email='$email'";
    $result = $conn->query($query);

    // Check if a matching user is found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if ($password === $user['password']) {
            // Start a session and store user information
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];

            // Redirect to a dashboard or any other page after successful login
            header('Location: index.php');
            exit();
        } else {
            // Invalid password
            echo "Invalid password";
        }
    } else {
        // User not found
        echo "Invalid email or password";
    }
}

// Close the database connection
$conn->close();
?>
