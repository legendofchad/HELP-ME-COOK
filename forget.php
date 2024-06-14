<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
    <?php
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

    if (isset($_POST['recover'])) {
        $email = trim($_POST['email']);
        $email = sanitize_input($email);
        $new_password = trim($_POST['new_password']);
        $new_password = sanitize_input($new_password);

        // Check if the email exists in the database
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Update the password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql_update = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
            if ($conn->query($sql_update) === TRUE) {
                echo "Password updated successfully.";
            } else {
                echo "Error updating password: " . $conn->error;
            }
        } else {
            echo "Email not found.";
        }
    }

    $conn->close();
    ?>
    <div class="login-container">
        <div class="illustration">
            <img src="images/chef.png" alt="Forgot Password">
        </div>
        <div class="login-form">
            <h1>Forgot Password</h1>
            <form action="" method="post">
                <div class="input-group">
                    <span class="icon envelope-icon"></span>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <span class="icon lock-icon"></span>
                    <input type="password" name="new_password" placeholder="New Password" required>
                </div>
                <button type="submit" name="recover">Recover Password</button>
            </form>
            <p style="text-align: center;">
                <a href="login.php">Back to Login</a>
            </p>
        </div>
    </div>
</body>

</html>
