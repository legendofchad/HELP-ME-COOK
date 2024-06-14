<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "psm";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $recipeName = $_POST['recipeName'];
    $category = $_POST['category'];
    $recipeIngredients = $_POST['recipeIngredients'];
    $recipeInstructions = $_POST['recipeInstructions'];
    $recipePhoto = $_FILES['recipePhoto']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["recipePhoto"]["name"]);

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES["recipePhoto"]["tmp_name"], $target_file)) {
        $recipeIngredientsStr = implode("\n", $recipeIngredients);
        $recipeInstructionsStr = implode("\n", $recipeInstructions);

        $stmt = $conn->prepare("INSERT INTO recipe_lib (recipe_name, category, recipe_ingredient, recipe_instruction, recipe_photo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $recipeName, $category, $recipeIngredientsStr, $recipeInstructionsStr, $recipePhoto);

        if ($stmt->execute()) {
            echo "<p>Recipe uploaded successfully!</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Sorry, there was an error uploading your file.</p>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Recipe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
    <link rel="stylesheet" type="text/css" href="styleProfile.css"/>
    <style>

    .form-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
        background-color: #e2e5de;
    }

    .upload-form {
        background: white;
        padding: 2em;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 100%;
        max-width: 600px;
    }

    .upload-form h1 {
        margin-bottom: 1em;
    }

    .upload-form .input-group {
        margin-bottom: 1em;
        position: relative;
    }

    .upload-form .input-group input[type="text"],
    .upload-form .input-group textarea,
    .upload-form .input-group input[type="file"] {
        padding: 0.75em;
        border: 1px solid #ddd;
        border-radius: 5px;
        width: calc(100% - 1.5em);
        font-size: 1.2em;
    }

    .upload-form .input-group textarea {
        resize: vertical;
    }

    .upload-form .dynamic-input-group {
        margin-bottom: 1em;
    }

    .upload-form .dynamic-input-group textarea {
        flex: 1;
    }

    .upload-form .dynamic-input-group button {
        margin-left: 1em;
        padding: 0.75em;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .upload-form .dynamic-input-group button:hover {
        background-color: #c82333;
    }

    .upload-form button {
        padding: 1em 2em;
        color: white;
        background-color: #5cb85c;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.2em;
    }

    .upload-form button:hover {
        background-color: #4cae4c;
    }

    .upload-form .add-more-group {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-bottom: 1em;
    }

    .upload-form .add-more-group button {
        margin-left: 0.5em;
    }

    .upload-form .add-more-group button.add-more {
        background-color: #5cb85c;
    }

    .upload-form .add-more-group button.add-more:hover {
        background-color: #4cae4c;
    }

    .upload-form .add-more-group button.delete {
        background-color: #dc3545;
    }

    .upload-form .add-more-group button.delete:hover {
        background-color: #c82333;
    }

    .edit button{
        padding: 1em 2em;
        color: white;
        background-color: #5cb85c;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.2em;
        justify-content: center;
    }
    </style>
</head>
<body>
<header>
    <nav-top>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        <label class="nav-logo">HELP ME COOK</label>
    </nav-top>
</header>
<main>
    <section class="form-container">
        <div class="upload-form">
            <h1>Upload Your Recipe</h1>
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
                <div class="input-group">
                    <input type="text" id="category" name="category" placeholder="Recipe Category" required>
                </div>

                <button type="submit">Submit Recipe</button>
            </form>
            
        </div>
    </section>
            <div class="edit">
                <button type="button" onclick="window.location.href='editRecipe.php'">Edit Recipe</button>
            </div>
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
</body>
</html>

