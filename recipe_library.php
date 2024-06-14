<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
    <link rel="stylesheet" href="style_library.css">
    <link rel="stylesheet" href="navbar.css">
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
<div class="search-container">
    <form action="" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search recipes...">
        <button type="submit"><i class="fa fa-search"></i></button>
    </form>
</div>
<div id="recipes">
<?php
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

// Fetch search term and category if they exist
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

// SQL query to fetch all recipes, optionally filtered by search term or category
$sql = "SELECT * FROM recipe_lib ";
if (!empty($searchTerm)) {
    $sql .= "WHERE recipe_name LIKE '%" . $conn->real_escape_string($searchTerm) . "%' ";
} elseif (!empty($categoryFilter)) {
    $sql .= "WHERE category = '" . $conn->real_escape_string($categoryFilter) . "' ";
}
$sql .= "ORDER BY category";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $currentCategory = "";
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        if ($currentCategory != $row['category']) {
            if ($currentCategory != "") {
                echo "</div></div></div>"; 
            }
            $currentCategory = $row['category'];
            $count = 0;
            echo "<div class='category'>";
            echo "<div class='category-header'><div class='category-title'>" . htmlspecialchars($currentCategory) . "</div>";
            echo "<div class='float-right viewmore'><a href='?category=" . htmlspecialchars($currentCategory) . "'>View more <i class='fas fa-angle-right ml-2'></i></a></div>";
            echo "</div><div class='recipe-list'>";
        }
        if (!empty($searchTerm) || !empty($categoryFilter) || $count < 3) {
            echo "<a href='recipe_details.php?id=" . htmlspecialchars($row['recipe_id']) . "' class='recipe-link'>";
            echo "<div class='recipe-card'>";
            echo "<img src='images/" . htmlspecialchars($row['recipe_photo']) . "' alt='" . htmlspecialchars($row['recipe_name']) . "'>";
            echo "<h4>" . htmlspecialchars($row['recipe_name']) . "</h4>";
            echo "</div>";
            echo "</a>";
            $count++;
        }
    }
    echo "</div></div></div>";
} else {
    echo "<p>No recipes found.</p>";
}
$conn->close();
?>
</div>
<script src="script.js"></script>
</body>
</html>
