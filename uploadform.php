<?php

include 'config.php';
session_start();

// Fetch category data from the database
$categoryQuery = "SELECT cat_id, cat_name FROM category";
$result = mysqli_query($conn, $categoryQuery);

// Check if there are any categories
if (mysqli_num_rows($result) > 0) {
    // Create an array to store the category options
    $categoryOptions = array();

    // Iterate through the category records
    while ($row = mysqli_fetch_assoc($result)) {
        $cat_id = $row['cat_id'];
        $catName = $row['cat_name'];

        // Add the category option to the array
        $categoryOptions[$cat_id] = $catName;
    }
}

if (isset($_POST['submit'])) {
    $recipe_name = $_POST['recipe_name'];
    $description = $_POST['description'];
    $cat_id = $_POST['cat_id'];
    $instruction = $_POST['instructions'];
    $user_id = $_SESSION['user_id'];

    // Menampung data file yang diupload
    $fileName = $_FILES['recipe_image']['name'];
    $tmpName = $_FILES['recipe_image']['tmp_name'];
    $imageExtension = explode('.', $fileName);
    $imageExtension = strtolower(end($imageExtension));
    //$imageExtension2 = $imageExtension[1];
    //$newname = 'recipe' . time() . '.' . $imageExtension2;

    // Tipe file yang diizinkan
    $validExtension = ['png', 'jpg', 'jpeg'];

    // Jika format file tidak diizinkan
    if (!in_array($imageExtension, $validExtension)) {
        $error = "Invalid Image Extension Format";
    } else {
        // Jika format sesuai
        $newImgName = uniqid();
        $newImgName .= '.' . $imageExtension;
        move_uploaded_file($tmpName, 'img/' . $newImgName);

        // Insert the recipe into the "recipe" table
        $sql4 = "INSERT INTO recipe (user_id, recipe_name, recipe_img, description, instructions, cat_id) 
        VALUES ('$user_id','$recipe_name', '$newImgName', '$description', '$instruction', $cat_id)";
        $q4 = mysqli_query($conn, $sql4);

        if ($q4) {
            // Get the recipe ID of the inserted recipe
            $recipeId = mysqli_insert_id($conn);

            // Insert the ingredients into the "bahan" table
            $ingredients = $_POST['ingredients'];

            foreach ($ingredients as $ingredient) {
                $ingredient = mysqli_real_escape_string($conn, $ingredient);
                $sql5 = "INSERT INTO bahan (resep_id, bahan_name) VALUES ($recipeId, '$ingredient')";
                mysqli_query($conn, $sql5);
            }

            $success = "Berhasil menambahkan";
            if ($success) {
                header("refresh:1;url=profile.php");
            }
        } else {
            echo "Gagal menambahkan resep";
        }
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recipe Upload Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/upload.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="asset/ThePinkPantry.png" height="50px">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto my-2 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="category.php">Category</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                </ul>
                <button class="btn2 mx-2" onclick="location.href='logout.php'" type="submit">Log Out</button>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <!-- Start Form -->

    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="judul text-center"><span class="headtitle">Upload</span><span> Your Recipe</span></div>
            <div class="mb-3">
                <label for="recipe_name" class="form-label">Recipe Name</label>
                <input type="text" id="recipe_name" class="form-control" name="recipe_name" placeholder="Recipe Name">
            </div>
            <div class="mb-3">
                <label for="recipe_image" class="form-label">Recipe Image</label>
                <input type="file" id="recipe_name" class="form-control" name="recipe_image" accept=".jpg, .jpeg, .png">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" id="description" class="form-control" name="description" placeholder="Description">
            </div>
            <div class="mb-3">
                <label for="ingredients" class="form-label">Ingredients</label>
                <div id="ingredientFields">
                    <div class="ingredient-field">
                        <input type="text" id="ingredients" class="form-control" name="ingredients[]" placeholder="ex: 1/2tsp Salt">
                        <button type="button" class="btn3" onclick="addIngredientField()">Add</button>
                        <!-- <button type="button" class="btn4" onclick="removeIngredientField(this)">Delete</button> -->
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="instructions" class="form-label">Instructions</label>
                <textarea id="instructions" class="form-control" name="instructions" placeholder="Instructions (descriptive)"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="cat_id" id="foodSelect">
                    <?php
                    // Iterate through the category options
                    foreach ($categoryOptions as $cat_id => $catName) {
                        echo '<option value="' . $cat_id . '">' . $catName . '</option>';
                    }
                    ?>
                </select>
            </div>
            <a href="recipe.php"><button type="submit" name="submit" class="btn1">Upload</button></a>
            <!-- <button type="submit" name="submit" class="btn1" onclick="location.href='recipe.php'">Upload</button> -->
        </form>
    </div>

    <script>
        function addIngredientField() {
            var container = document.getElementById('ingredientFields');
            var newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.className = 'form-control';
            newInput.name = 'ingredients[]';
            newInput.placeholder = 'Ingredients';
            container.appendChild(newInput);
        }

        function removeIngredientField(button) {
            var fieldContainer = button.parentNode;
            fieldContainer.remove();
        }

        var ingredientFieldCounter = 1;

        function addIngredientField() {
            var fieldContainer = document.createElement('div');
            fieldContainer.className = 'ingredient-field';

            var ingredientInput = document.createElement('input');
            ingredientInput.type = 'text';
            ingredientInput.className = 'form-control';
            ingredientInput.name = 'ingredients[]';
            ingredientInput.placeholder = 'Ingredients';

            var addButton = document.createElement('button');
            addButton.type = 'button';
            addButton.className = 'btn3';
            addButton.textContent = 'Add';
            addButton.onclick = function() {
                addIngredientField(addButton);
            };

            var deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.className = 'btn4';
            deleteButton.textContent = 'Delete';
            deleteButton.onclick = function() {
                removeIngredientField(deleteButton);
            };

            fieldContainer.appendChild(ingredientInput);
            fieldContainer.appendChild(addButton);
            fieldContainer.appendChild(deleteButton);

            var ingredientFieldsContainer = document.getElementById('ingredientFields');
            ingredientFieldsContainer.appendChild(fieldContainer);

            ingredientFieldCounter++;
        }
    </script>


    <!-- End Form -->