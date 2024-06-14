<?php
session_start();

$host = "localhost"; 
$username = "root";
$password = ""; 
$database = "psm"; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitize_input($input)
{
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($input));
}

function authenticate_user($username, $password)
{
    global $conn;
    $username = sanitize_input($username);
    $password = sanitize_input($password);

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            return $row['userId']; // Return user ID instead of true
        } else {
            return "Invalid password";
        }
    } else {
        return "Invalid username";
    }
}

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $auth_result = authenticate_user($username, $password);

    if (is_numeric($auth_result)) {
        $_SESSION['userId'] = $auth_result; // Store user ID in session
        header("Location: recipe_library.php");
        exit();
    } else {
        echo $auth_result; // Display the error message
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <div class="login-container">
        <div class="illustration">
            <img src="images/chef.png" alt="Chef">
        </div>
        <div class="login-form">
            <h1>Login</h1>
            <form action="" method="post">
                <div class="input-group">
                    <span class="icon user-icon"></span>
                    <input type="text" name="username" placeholder="username" required>
                </div>
                <div class="input-group">
                    <span class="icon lock-icon"></span>
                    <input type="password" name="password" placeholder="password" required>
                </div>
                <button type="submit" name="login">LOGIN</button>
            </form>
            <p style="text-align: center;">
                <a href="forget.php" class="link-style">Forgot password?</a>
            </p>
            <p style="text-align: center;">
                <a href="signup.php" class="link-style">Don't have an account?</a>
            </p>
        </div>
    </div>
</body>
</html>
