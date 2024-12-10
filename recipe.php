<?php

include 'config.php';
session_start();

// get recipeId from clicked card
$recipeid = $_GET['recipe_id'];

// check resep yang ditampilkan adalah resep yang diklik di halaman index / category
$getrecipe = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM recipe WHERE recipe_id = '$recipeid'"));
$recipeuser = $getrecipe['user_id'];
$geting = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bahan WHERE resep_id = '$recipeid'"));
$bahanid = $geting['bahan_id'];
$getuser = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$recipeuser'"));
$userid = $getuser['user_id'];

$rows = mysqli_query($conn, "SELECT * FROM recipe WHERE recipe_id = $recipeid");
$result = mysqli_fetch_assoc($rows);

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recipe Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/recipe.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="asset/ThePinkPantry.png" height="90px">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto my-2 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="category.php">Category</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                </ul>
                <?php
                if (isset($_SESSION['username'])) {
                    // User is signed in, show logout button
                    echo '<button class="btn2 mx-2" onclick="location.href=\'logout.php\'" type="submit">Log Out</button>';
                } else {
                    // User is not signed in, show sign in and sign up buttons
                    echo '<button class="btn1 mx-2" onclick="location.href=\'signin.php\'" type="submit">Sign In</button>';
                    echo '<button class="btn2 mx-2" onclick="location.href=\'signup.php\'" type="submit">Sign Up</button>';
                }
                ?>
            </div>
        </div>
    </nav>


    <!-- End Navbar -->

    <!-- Start Header/Title -->

    <section class="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 d-flex">
                    <img src="img/<?php echo $result['recipe_img']; ?>" title="<?php echo $result['recipe_img']; ?>" height="150px">
                </div>
                <div class="col-lg-5 align-self-center profile">
                    <?php echo "<h1>" . $getrecipe['recipe_name'] . "</h1>" ?>
                    <?php echo "<h4>" . $getuser['username'] . "</h4>" ?>
                </div>
            </div>
        </div>
    </section>

    <!-- End Header/Title -->


    
    <!-- Start Description -->

    <section class="desc mt-4">
        <div class="container">
            <div class="row p-3">
                <h4>Description</h4>
                <?php echo "<div>" . $getrecipe['description'] . "</div>"; ?>
            </div>
        </div>
    </section>

    <!-- End Description -->

    <!-- Start Ingredients -->

    <section class="Ingredients mt-4">
        <div class="container">
            <div class="row p-3">
                <h4>Ingredients</h4>
                <?php
                // php ini harus diisi apa?
                $ingredients = mysqli_query($conn, "SELECT * FROM bahan WHERE resep_id = '$recipeid'");
                while ($ingredient = mysqli_fetch_assoc($ingredients)) {
                    echo "<li>" . $ingredient['bahan_name'] . "</li>";
                }
                ?>
            </div>
    </section>

    <!-- End Ingredients -->

    <!-- Start Instructions -->

    <section class="Instructions m-4">
        <div class="container">
            <div class="row p-3">
                <h4>Instructions</h4>
                <?php echo "<div>" . $getrecipe['instructions'] . "</div>"; ?>
            </div>
        </div>
    </section>

    <!-- End Instructions -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>