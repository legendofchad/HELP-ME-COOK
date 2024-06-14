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
    <title>Upload Recipe</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
    />
    <link rel="stylesheet" type="text/css" href="navbar.css"/>
    <link rel="stylesheet" type="text/css" href="style_post.css"/>
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
        <div class="upload-form">
            <h1>Upload Your Recipe</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";  
    $username = "root";         
    $password = "";             
    $dbname = "psm";            

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $recipeName = $_POST['recipeName'];
    $recipeIngredients = $_POST['recipeIngredients'];
    $recipeInstructions = $_POST['recipeInstructions'];
    $recipePhoto = $_FILES['recipePhoto']['name'];
    $userId = $_SESSION['userId']; // Retrieve the userId from the session
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["recipePhoto"]["name"]);

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES["recipePhoto"]["tmp_name"], $target_file)) {
        
        $recipeIngredientsStr = implode("\n", $recipeIngredients);
        $recipeInstructionsStr = implode("\n", $recipeInstructions);

        $stmt = $conn->prepare("INSERT INTO user_recipe (RecipeName, RecipeIngredients, RecipePhoto, RecipeInstructions, userId) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $recipeName, $recipeIngredientsStr, $recipePhoto, $recipeInstructionsStr, $userId);

        if ($stmt->execute()) {
            echo "<p>Recipe uploaded successfully!</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Sorry, there was an error uploading your file.</p>";
    }

    // Close the connection
    $conn->close();
}
?>

            <form id="recipeForm" method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <input type="text" id="recipeName" name="recipeName" placeholder="Recipe Name" required>
                </div>
                <div id="ingredientContainer" class="input-group">
                    <div class="dynamic-input-group">
                        <textarea name="recipeIngredients[]" placeholder="Ingredient 1" required></textarea>
                    </div>
                    <div class="dynamic-input-group">
                        <textarea name="recipeIngredients[]" placeholder="Ingredient 2" required></textarea>
                    </div>
                    <div class="dynamic-input-group">
                        <textarea name="recipeIngredients[]" placeholder="Ingredient 3" required></textarea>
                    </div>
                </div>
                <div class="add-more-group">
                    <button type="button" class="add-more" id="addIngredientButton" onclick="addIngredientField()">Add More</button>
                    <button type="button" class="delete" id="deleteIngredientButton" onclick="deleteIngredientField()">Delete</button>
                </div>
                <div id="instructionContainer" class="input-group">
                    <div class="dynamic-input-group">
                        <textarea name="recipeInstructions[]" placeholder="Instruction 1" required></textarea>
                    </div>
                </div>
                <div class="add-more-group">
                    <button type="button" class="add-more" id="addInstructionButton" onclick="addInstructionField()">Add More</button>
                    <button type="button" class="delete" id="deleteInstructionButton" onclick="deleteInstructionField()">Delete</button>
                </div>
                <div class="input-group">
                    <input type="file" id="recipePhoto" name="recipePhoto" accept="image/*" required>
                </div>
                <button type="submit">Submit Recipe</button>
            </form>
        </div>
    </section>
</main>

<script>
let ingredientCount = 3;
let instructionCount = 1;

function addIngredientField() {
    ingredientCount++;
    var container = document.getElementById("ingredientContainer");
    var newInputGroup = document.createElement("div");
    newInputGroup.className = "dynamic-input-group";
    
    var newTextArea = document.createElement("textarea");
    newTextArea.name = "recipeIngredients[]";
    newTextArea.placeholder = "Ingredient " + ingredientCount;
    newTextArea.required = true;

    newInputGroup.appendChild(newTextArea);
    container.appendChild(newInputGroup);
}

function deleteIngredientField() {
    var container = document.getElementById("ingredientContainer");
    if (ingredientCount > 3) {
        container.removeChild(container.lastElementChild);
        ingredientCount--;
    }
}

function addInstructionField() {
    instructionCount++;
    var container = document.getElementById("instructionContainer");
    var newInputGroup = document.createElement("div");
    newInputGroup.className = "dynamic-input-group";
    
    var newTextArea = document.createElement("textarea");
    newTextArea.name = "recipeInstructions[]";
    newTextArea.placeholder = "Instruction " + instructionCount;
    newTextArea.required = true;

    newInputGroup.appendChild(newTextArea);
    container.appendChild(newInputGroup);
}

function deleteInstructionField() {
    var container = document.getElementById("instructionContainer");
    if (instructionCount > 1) {
        container.removeChild(container.lastElementChild);
        instructionCount--;
    }
}
</script>
<script src="script.js"></script>
</body>
</html>
