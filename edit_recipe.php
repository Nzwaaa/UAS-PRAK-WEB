<?php
include 'config.php';
session_start();

// Pastikan ada parameter recipe_id di URL
if (isset($_GET['recipe_id'])) {
    $recipeId = $_GET['recipe_id'];

    // Ambil data resep berdasarkan recipe_id
    $recipeQuery = "SELECT * FROM recipe WHERE recipe_id = $recipeId";
    $recipeResult = mysqli_query($conn, $recipeQuery);
    $recipeData = mysqli_fetch_assoc($recipeResult);

    // Ambil kategori yang terkait dengan resep
    $categoryQuery = "SELECT cat_id, cat_name FROM category";
    $categoryResult = mysqli_query($conn, $categoryQuery);
    $categoryOptions = [];
    while ($row = mysqli_fetch_assoc($categoryResult)) {
        $categoryOptions[$row['cat_id']] = $row['cat_name'];
    }
}

if (isset($_POST['submit'])) {
    $recipe_name = $_POST['recipe_name'];
    $description = $_POST['description'];
    $cat_id = $_POST['cat_id'];
    $instruction = $_POST['instructions'];
    $user_id = $_SESSION['user_id'];

    // Menangani upload gambar jika ada
    if ($_FILES['recipe_image']['name'] != "") {
        $fileName = $_FILES['recipe_image']['name'];
        $tmpName = $_FILES['recipe_image']['tmp_name'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));
        $validExtension = ['png', 'jpg', 'jpeg'];

        if (in_array($imageExtension, $validExtension)) {
            $newImgName = uniqid() . '.' . $imageExtension;
            move_uploaded_file($tmpName, 'img/' . $newImgName);
        } else {
            $error = "Invalid Image Extension Format";
        }
    } else {
        // Jika gambar tidak diubah, gunakan gambar yang lama
        $newImgName = $recipeData['recipe_img'];
    }

    // Update data resep
    $sql = "UPDATE recipe SET recipe_name = '$recipe_name', recipe_img = '$newImgName', description = '$description', instructions = '$instruction', cat_id = $cat_id WHERE recipe_id = $recipeId";
    $q = mysqli_query($conn, $sql);

    if ($q) {
        header("Location: profile.php"); // Redirect ke halaman profil setelah berhasil
    } else {
        echo "Gagal mengupdate resep";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Recipe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="category.php">Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                </ul>
                <button class="btn2 mx-2" onclick="location.href='logout.php'" type="submit">Log Out</button>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <!-- Start Edit Form -->
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="judul text-center"><span class="headtitle">Edit</span><span> Your Recipe</span></div>

            <!-- Recipe Name -->
            <div class="mb-3">
                <label for="recipe_name" class="form-label">Recipe Name</label>
                <input type="text" id="recipe_name" class="form-control" name="recipe_name" value="<?php echo $recipeData['recipe_name']; ?>" placeholder="Recipe Name">
            </div>

            <!-- Recipe Author -->
            <div class="mb-3">
                <label for="recipe_author" class="form-label">Recipe Author</label>
                <input type="text" id="recipe_author" class="form-control" name="recipe_author" value="<?php echo $recipeData['recipe_author']; ?>" placeholder="Recipe Author">
            </div>

            <!-- Recipe Image -->
            <div class="mb-3">
                <label for="recipe_image" class="form-label">Recipe Image</label>
                <input type="file" class="form-control" name="recipe_image" accept=".jpg, .jpeg, .png">
                <img src="img/<?php echo $recipeData['recipe_img']; ?>" alt="Current Image" class="mt-2" width="100">
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" id="description" class="form-control" name="description" value="<?php echo $recipeData['description']; ?>" placeholder="Description">
            </div>

            <!-- Ingredients -->
            <div class="mb-3">
                <label for="ingredients" class="form-label">Ingredients</label>
                <div id="ingredientFields">
                    <?php
                    // Ambil dan tampilkan bahan resep
                    $ingredientQuery = "SELECT * FROM bahan WHERE resep_id = $recipeId";
                    $ingredientResult = mysqli_query($conn, $ingredientQuery);
                    while ($ingredient = mysqli_fetch_assoc($ingredientResult)) {
                        echo '<input type="text" class="form-control mb-2" name="ingredients[]" value="' . $ingredient['bahan_name'] . '" placeholder="ex: 1/2tsp Salt">';
                    }
                    ?>
                    <button type="button" class="btn3" onclick="addIngredientField()">Add</button>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mb-3">
                <label for="instructions" class="form-label">Instructions</label>
                <textarea id="instructions" class="form-control" name="instructions" placeholder="Instructions (descriptive)"><?php echo $recipeData['instructions']; ?></textarea>
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="cat_id" id="foodSelect" class="form-select">
                    <?php
                    // Pilih kategori yang sudah ada pada resep
                    foreach ($categoryOptions as $cat_id => $catName) {
                        $selected = ($cat_id == $recipeData['cat_id']) ? 'selected' : '';
                        echo '<option value="' . $cat_id . '" ' . $selected . '>' . $catName . '</option>';
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="submit" class="btn1">Update</button>
        </form>
    </div>
    <!-- End Edit Form -->

    <script>
        function addIngredientField() {
            var container = document.getElementById('ingredientFields');
            var newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.className = 'form-control mb-2';
            newInput.name = 'ingredients[]';
            newInput.placeholder = 'Ingredients';
            container.appendChild(newInput);
        }
    </script>
</body>
</html>
