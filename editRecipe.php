<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psm";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recipeId = '';
$recipeData = ['recipe_name' => '', 'category' => '', 'recipe_ingredient' => '', 'recipe_instruction' => '', 'recipe_photo' => ''];
$result = null; // Initialize $result to null

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fetchById'])) {
    $recipeId = $_POST['recipeId'];
    $stmt = $conn->prepare("SELECT * FROM recipe_lib WHERE recipe_id = ?");
    $stmt->bind_param("i", $recipeId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $recipeData = $result->fetch_assoc();
    } else {
        echo "<p>Recipe not found.</p>";
    }
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    // Handle photo update if provided
    if (isset($_FILES['recipePhoto']) && $_FILES['recipePhoto']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['recipePhoto']['name']);
        if (move_uploaded_file($_FILES['recipePhoto']['tmp_name'], $uploadFile)) {
            $newPhotoFilename = basename($_FILES['recipePhoto']['name']);
        } else {
            echo "Failed to upload photo.";
        }
    }

    // Update other recipe details
    $recipeId = $_POST['recipeId'];
    $recipeName = $_POST['recipeName'];
    $category = $_POST['category'];
    $recipeIngredients = $_POST['recipeIngredients'];
    $recipeInstructions = $_POST['recipeInstructions'];
    $recipeIngredientsStr = implode("\n", $recipeIngredients);
    $recipeInstructionsStr = implode("\n", $recipeInstructions);
    $stmt = $conn->prepare("UPDATE recipe_lib SET recipe_name=?, category=?, recipe_ingredient=?, recipe_instruction=?, recipe_photo=? WHERE recipe_id=?");
    $stmt->bind_param("sssssi", $recipeName, $category, $recipeIngredientsStr, $recipeInstructionsStr, $newPhotoFilename, $recipeId);
    if ($stmt->execute()) {
        echo "<p>Recipe updated successfully!</p>";
    } else {
        echo "<p>Error updating recipe: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
    <style>
        body {
    width: 100%;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    min-height: 100vh;
    font-family: "Poppins", sans-serif;
    background-color: #e2e5de;
}
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
        nav-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    background-color: #0cae87;
    height: 90px;
    color: white;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-bottom-left-radius: 30px;
    border-bottom-right-radius: 30px;
}


/* Menu icon styling */

.checkbtn {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
    color: white;
    font-size: 30px;
}


/* Logo styling */

label.nav-logo {
    flex-grow: 1;
    text-align: center;
    font-size: 70px;
    font-weight: bold;
}

.fa-bars {
    background: transparent;
}

#check {
    display: none;
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
<div class="form-container">
    <div class="upload-form">
        <h1>Edit Recipe</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="input-group">
                <input type="number" name="recipeId" placeholder="Enter Recipe ID" required>
                <button type="submit" name="fetchById">Fetch by ID</button>
            </div>
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fetchById']) && $result && $result->num_rows > 0): ?>
                <div class="input-group">
                    <input type="text" name="recipeName" value="<?php echo htmlspecialchars($recipeData['recipe_name']); ?>" required>
                </div>
                <div class="input-group">
                    <input type="text" name="category" value="<?php echo htmlspecialchars($recipeData['category']); ?>" required>
                </div>
                <div class="input-group">
                    <textarea name="recipeIngredients[]" required><?php echo htmlspecialchars(implode("\n", explode("\n", $recipeData['recipe_ingredient']))); ?></textarea>
                </div>
                <div class="input-group">
                    <textarea name="recipeInstructions[]" required><?php echo htmlspecialchars(implode("\n", explode("\n", $recipeData['recipe_instruction']))); ?></textarea>
                </div>
                <div class="input-group">
                    <input type="file" name="recipePhoto" accept="image/*">
                </div>
                <button type="submit" name="update">Update Recipe</button>
            <?php endif; ?>
        </form>
    </div>
</div>
</body>
</html>
