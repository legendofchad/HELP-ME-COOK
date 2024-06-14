<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <div class="login-container">
        <div class="illustration">
            <img src="images/chef.png" alt="Chef">
        </div>
        <div class="login-form">
            <h1>Sign Up</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="input-group">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <div class="input-group">
                    <input type="file" name="profile_image" required>
                </div>
                <div class="input-group">
                    <input type="text" name="biodata" placeholder="About you" required maxlength="500">
                </div>
                <button type="submit" name="signup">SIGN UP</button>
                <p style="text-align: center;">
                    <a href="login.php" class="link-style">Already have an account?</a>
                </p>
            </form>
        </div>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
        $host = "localhost"; 
        $dbUsername = "root"; 
        $dbPassword = ""; 
        $database = "psm";

        $conn = new mysqli($host, $dbUsername, $dbPassword, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirm_password"];
        $biodata = mysqli_real_escape_string($conn, $_POST["biodata"]);
        $target_dir = "uploads/";
        $profile_image = $target_dir . basename($_FILES["profile_image"]["name"]);

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if ($password != $confirmPassword) {
            echo "Error: Passwords do not match.";
        } else {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $profile_image)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (email, username, password, profile_image, biodata) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $email, $username, $hashedPassword, $profile_image, $biodata);

                if ($stmt->execute()) {
                    header("Location: login.php");
                    exit();
                } else {
                    echo "Execute error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "<p>Sorry, there was an error uploading your file.</p>";
            }
        }
        $conn->close();
    }
    ?>
</body>
</html>
