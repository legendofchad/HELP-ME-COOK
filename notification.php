<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userId'];
$host = "localhost";
$username = "root";
$password = "";
$database = "psm";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Error handling function
function queryWithErrorHandling($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        echo "Error: " . $conn->error;
        return false;
    }
    return $result;
}

$sql_likes = "SELECT u.username, u.profile_image, r.RecipeName, rl.num_of_likes, rl.created_at 
              FROM recipe_likes rl 
              JOIN users u ON rl.UserId = u.userId 
              JOIN user_recipe r ON rl.RecipeId = r.RecipeId 
              WHERE r.UserId = $userId 
              ORDER BY rl.created_at DESC";

$sql_comments = "SELECT u.username, u.profile_image, r.RecipeName, rc.comment, rc.created_at 
                 FROM recipe_comments rc 
                 JOIN users u ON rc.UserId = u.userId 
                 JOIN user_recipe r ON rc.RecipeId = r.RecipeId 
                 WHERE r.UserId = $userId 
                 ORDER BY rc.created_at DESC";

$likes_result = queryWithErrorHandling($conn, $sql_likes);
$comments_result = queryWithErrorHandling($conn, $sql_comments);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
    <link rel="stylesheet" type="text/css" href="navbar.css"/>
    <link rel="stylesheet" type="text/css" href="style_notification.css"/>
</head>
<body>
<header>
    <button class="checkbtn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <label class="nav-logo">HELP ME COOK</label>
</header>

<div id="sidebar_menu_bg" onclick="toggleSidebar()"></div>

<div class="sidebar" id="sidebar">
    <button class="btn btn-radius btn-sm btn-secondary toggle-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-angle-left mr-2"></i>Close menu</button>
    <div class="sb-setting">
        <div class="header-setting">
            <ul class="hs-toggles">
                <li class="hst-item" data-toggle="tooltip" title="Chatbot">
                    <a href="ai.php">
                        <div class="name"><span>Chatbot</span></div>
                    </a>
                </li>
                <li class="hst-item" data-toggle="tooltip" title="Recipe Library">
                    <a href="recipe_library.php">
                        <div class="name"><span>Recipe Library</span></div>
                    </a>
                </li>
                <li class="hst-item" data-toggle="tooltip" title="User Creation">
                    <a href="userCreation.php">
                        <div class="name"><span>User Creation</span></div>
                    </a>
                </li>
                <li class="hst-item" data-toggle="tooltip" title="Notification">
                    <a href="notification.php">
                        <div class="name"><span>Notification</span></div>
                    </a>
                </li>
                <li class="hst-item" data-toggle="tooltip" title="Profile">
                    <a href="profile.php">
                        <div class="name"><span>Profile</span></div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-logout">
        <form action="logout.php" method="post">
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</div>

<div id="notifications">
    <?php
    if ($likes_result && $likes_result->num_rows > 0) {
        while ($like = $likes_result->fetch_assoc()) {
            $formatted_date = date('d/m', strtotime($like['created_at']));
            echo "<div class='notification'><img src='{$like['profile_image']}' alt='Profile Photo' class='notification-img'><div class='notification-details'><strong>{$like['username']}</strong> liked <strong>{$like['RecipeName']}</strong></div><small>{$formatted_date}</small></div>";
        }
    }
    if ($comments_result && $comments_result->num_rows > 0) {
        while ($comment = $comments_result->fetch_assoc()) {
            $formatted_date = date('d/m', strtotime($comment['created_at']));
            echo "<div class='notification'><img src='{$comment['profile_image']}' alt='Profile Photo' class='notification-img'><div class='notification-details'><strong>{$comment['username']}</strong> commented on <strong>{$comment['RecipeName']}</strong><div class='comment'>Comment: {$comment['comment']}</div></div><small>{$formatted_date}</small></div>";
        }
    }
    ?>
</div>
<script src="script.js"></script>
</body>
</html>

<?php
$conn->close();
?>
