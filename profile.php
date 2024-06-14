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

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$sqlUser = "SELECT username, biodata, profile_image FROM users WHERE userId = $userId";
$resultUser = $conn->query($sqlUser);

if ($resultUser === false) {
    echo "Error: " . $conn->error . "<br>";
    $username = '';
    $biodata = '';
    $profile_image = '';
} else {
    if ($resultUser->num_rows > 0) {
        $rowUser = $resultUser->fetch_assoc();
        $username = $rowUser["username"];
        $biodata = $rowUser["biodata"];
        $profile_image = $rowUser["profile_image"];
    } else {
        echo "User not found<br>";
        $username = '';
        $biodata = '';
        $profile_image = '';
    }
}

// Fetch recipes
$sqlRecipe = "SELECT RecipeName, RecipeIngredients, RecipePhoto FROM user_recipe WHERE userId = $userId";
$resultRecipe = $conn->query($sqlRecipe);

$recipes = [];
if ($resultRecipe !== false && $resultRecipe->num_rows > 0) {
    while ($rowRecipe = $resultRecipe->fetch_assoc()) {
        $recipes[] = $rowRecipe;
    }
} else {
    echo "No recipes found<br>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
    <link rel="stylesheet" type="text/css" href="style_profile.css">
    <link rel="stylesheet" type="text/css" href="navbar.css"/>
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
        <i class="fas fa-angle-left mr-2"></i>Close menu
    </button>
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
<div class="header__wrapper">
    <div class="profile__container">
        <div class="left__col">
            <div class="img__container">
                <img src="<?php echo $profile_image; ?>" alt="Profile Image" class="profile__image"/>
            </div>
            <h2><?php echo $username; ?></h2>
            <p class="biodata">
                <?php echo $biodata; ?>
            </p>
            <button id="editButton">Edit</button>
        </div>
        <div class="right__col">
    <nav>
        <ul>
            <li><a href="#">Post</a></li>
        </ul>
    </nav>
            <div class="posts">
                    <?php foreach ($recipes as $recipe): ?>
                        <div class="post-card">
                            <img src="uploads/<?php echo $recipe['RecipePhoto']; ?>" alt="<?php echo htmlspecialchars($recipe['RecipeName']); ?>">
                            <h3><?php echo $recipe['RecipeName']; ?></h3>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="editForm" style="display: none;">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
        <label for="newUsername">New Username:</label>
        <input type="text" name="new_username" id="newUsername" value="<?php echo $username; ?>">
        
        <label for="newBiodata">New Biodata:</label>
        <input type="text" name="new_biodata" id="newBiodata" value="<?php echo $biodata; ?>">
        
        <label for="profileImage">Profile Image:</label>
        <input type of "file" name="profile_image" id="profileImage">
        
        <input type="hidden" name="current_image" value="<?php echo $profile_image; ?>">
        
        <button type="submit">Save Changes</button>
    </form>
</div>

<script>
document.getElementById('editButton').addEventListener('click', function () {
    document.getElementById('editForm').style.display = 'block';
});
</script>
<script src="script.js"></script>
</body>
</html>
