<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psm";

// Create and check connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch recipe ID from URL
$recipeId = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($recipeId)) {
    echo "<p>Recipe not found. No recipe ID provided.</p>";
    exit();
}

// SQL query to fetch the recipe details
$sql = "SELECT * FROM recipe_lib WHERE recipe_id = " . $conn->real_escape_string($recipeId);
$result = $conn->query($sql);

if ($result === false) {
    echo "Error: " . $conn->error;
    exit();
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($row['recipe_name']); ?></title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
        <link rel="stylesheet" href="style_library.css">
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

    <div id="recipe-details">
        <h1><?php echo htmlspecialchars($row['recipe_name']); ?></h1>
        <img src="images/<?php echo htmlspecialchars($row['recipe_photo']); ?>" alt="<?php echo htmlspecialchars($row['recipe_name']); ?>">
        <h3>Ingredients</h3>
        <ul class="ingredient-list">
            <?php
            $ingredients = explode("\n", $row['recipe_ingredient']);
            foreach ($ingredients as $ingredient) {
                echo "<li>" . htmlspecialchars(trim($ingredient)) . "</li>";
            }
            ?>
        </ul>
        <h3>Instructions</h3>
        <ol class="instruction-list">
            <?php
            $instructions = explode("\n", $row['recipe_instruction']);
            foreach ($instructions as $instruction) {
                echo "<li>" . htmlspecialchars(trim($instruction)) . "</li>";
            }
            ?>
        </ol>
    </div>
    <script src="script.js"></script>
    </body>
    </html>
    <?php
} else {
    echo "<p>Recipe not found.</p>";
}
$conn->close();
?>
