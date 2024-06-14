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
    <title>Recipe Viewer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
    <link rel="stylesheet" type="text/css" href="navbar.css"/>
    <link rel="stylesheet" type="text/css" href="style_usercreation.css"/>
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
<main>
    <section class="form-container">
        <div class="recipe-feed">
        <?php
        $userId = $_SESSION['userId'];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "psm";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle like submission
        if (isset($_POST['like'])) {
            $recipeId = intval($_POST['recipe_id']);
            // Check if the user has already liked the recipe
            $checkLikeSql = "SELECT * FROM recipe_likes WHERE RecipeId = ? AND UserId = ?";
            $checkLikeStmt = $conn->prepare($checkLikeSql);
            $checkLikeStmt->bind_param("ii", $recipeId, $userId);
            $checkLikeStmt->execute();
            $checkLikeResult = $checkLikeStmt->get_result();

            if ($checkLikeResult->num_rows == 0) {
                // Insert the like
                $likeSql = "INSERT INTO recipe_likes (RecipeId, UserId, num_of_likes) VALUES (?, ?, 1)
                            ON DUPLICATE KEY UPDATE num_of_likes = num_of_likes + 1";
                $likeStmt = $conn->prepare($likeSql);
                $likeStmt->bind_param("ii", $recipeId, $userId);
                $likeStmt->execute();
                $likeStmt->close();
            }
            $checkLikeStmt->close();
        }

        // Handle comment submission
        if (isset($_POST['comment'])) {
            $recipeId = intval($_POST['recipe_id']);
            $comment = htmlspecialchars($_POST['comment_text']);
            $commentSql = "INSERT INTO recipe_comments (RecipeId, UserId, comment) VALUES (?, ?, ?)";
            $commentStmt = $conn->prepare($commentSql);
            $commentStmt->bind_param("iis", $recipeId, $userId, $comment);
            $commentStmt->execute();
            $commentStmt->close();
        }

        // Get the current recipe ID from the URL, default to 1 if not set
        $currentRecipe = isset($_GET['id']) ? intval($_GET['id']) : 1;

        // Fetch the minimum and maximum recipe IDs
        $minMaxSql = "SELECT MIN(RecipeId) AS MinId, MAX(RecipeId) AS MaxId FROM user_recipe";
        $minMaxResult = $conn->query($minMaxSql);
        if ($minMaxResult->num_rows > 0) {
            $minMaxRow = $minMaxResult->fetch_assoc();
            $minId = $minMaxRow['MinId'];
            $maxId = $minMaxRow['MaxId'];
        } else {
            die("Failed to retrieve recipe ID boundaries.");
        }

        // Prepare and execute the SQL query with error handling
        $sql = "SELECT ur.*, u.username 
                FROM user_recipe ur 
                JOIN users u ON ur.UserId = u.UserId 
                WHERE ur.RecipeId = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("MySQL prepare error: " . $conn->error);
        }

        $stmt->bind_param("i", $currentRecipe);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<div class='recipe-card'>";
            echo "<div class='recipe-image'>";
            echo "<img src='uploads/" . htmlspecialchars($row['RecipePhoto']) . "' alt='Recipe Photo'>";
            echo "</div>";
            echo "<div class='recipe-content'>";
            echo "<h2>" . htmlspecialchars($row['RecipeName']) . "</h2>";
            echo "<p>Uploaded by: " . htmlspecialchars($row['username']) . "</p>";

            // Display Ingredients
            echo "<div class='ingredient-section'>Ingredients:</div>";
            echo "<ol class='ingredient-list'>";
            $ingredients = explode("\n", $row['RecipeIngredients']);
            foreach ($ingredients as $ingredient) {
                echo "<li>" . htmlspecialchars($ingredient) . "</li>";
            }
            echo "</ol>";

            // Display Instructions
            echo "<div class='instruction-section'>Instructions:</div>";
            echo "<ol class='instruction-list'>";
            $instructions = explode("\n", $row['RecipeInstructions']);
            foreach ($instructions as $instruction) {
                echo "<li>" . htmlspecialchars($instruction) . "</li>";
            }
            echo "</ol>";

            echo "</div>";
            echo "</div>";

            // Fetch likes
            $likeSql = "SELECT SUM(num_of_likes) AS total_likes FROM recipe_likes WHERE RecipeId = ?";
            $likeStmt = $conn->prepare($likeSql);
            $likeStmt->bind_param("i", $currentRecipe);
            $likeStmt->execute();
            $likeResult = $likeStmt->get_result();
            $likes = $likeResult->num_rows > 0 ? $likeResult->fetch_assoc()['total_likes'] : 0;
            $likeStmt->close();

            // Fetch comments
            $commentSql = "SELECT rc.comment, u.username FROM recipe_comments rc
                        JOIN users u ON rc.UserId = u.UserId
                        WHERE rc.RecipeId = ?";
            $commentStmt = $conn->prepare($commentSql);
            $commentStmt->bind_param("i", $currentRecipe);
            $commentStmt->execute();
            $commentResult = $commentStmt->get_result();
            $comments = [];
            while ($commentRow = $commentResult->fetch_assoc()) {
                $comments[] = [
                    'comment' => htmlspecialchars($commentRow['comment']),
                    'username' => htmlspecialchars($commentRow['username']),
                ];
            }
            $commentStmt->close();

            // Display like and comment section
            echo "<div class='like-comment-section'>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='recipe_id' value='$currentRecipe'>";
            echo "<button type='submit' name='like'><i class='fas fa-heart'></i></button> $likes Likes";
            echo "</form>";

            echo "<button onclick='showCommentForm()'><i class='fas fa-comment'></i> Comment</button>";
            echo "<div class='comment-form' id='commentForm'>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='recipe_id' value='$currentRecipe'>";
            echo "<textarea name='comment_text' required></textarea>";
            echo "<button type='submit' name='comment'>Submit</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";

            // Display comments
            echo "<div class='comments'>";
            echo "<ul>";
            foreach ($comments as $comment) {
                echo "<li>";
                echo "<strong>" . $comment['username'] . "</strong>: " . $comment['comment'];
                echo "</li>";
            }
            echo "</ul>";
            echo "</div>";
        } else {
            echo "<p>No recipes found.</p>";
        }

        // Navigation buttons
        echo "<div class='navigation-buttons'>";
        if ($currentRecipe > $minId) {
            echo "<button onclick='navigateRecipe(-1)'>Previous</button>";
        }
        if ($currentRecipe < $maxId) {
            echo "<button onclick='navigateRecipe(1)'>Next</button>";
        }
        echo "</div>";

        $stmt->close();
        $conn->close();
        ?>

        <script>
            function navigateRecipe(direction) {
                let currentId = parseInt("<?php echo $currentRecipe; ?>");
                window.location.href = '?id=' + (currentId + direction);
            }

            function showCommentForm() {
                document.getElementById('commentForm').style.display = 'block';
            }
        </script>
        </div>
    </section>
</main>
<script src="script.js"></script>
</body>
</html>
