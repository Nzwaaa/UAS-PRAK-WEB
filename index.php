<?php

include 'config.php';
session_start();

// Fetch recipes data from database
$i = 1;
$rows = mysqli_query($conn, "SELECT * FROM recipe ORDER BY recipe_id DESC LIMIT 4");

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CooKING WebApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
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
                        <a class="nav-link" onclick="handleCategoryClick()">Category</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" onclick="handleProfileClick()">Profile</a>
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

    <!-- Start Content -->

    <section class="main">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 text-center">
                    <h1 id="typed-text">Discover<br>Delicious Recipes</h1>
                    <form class="search" role="search" action="category.php" method="get" onsubmit="return handleSearchSubmit()">
                        <input class="form-control" type="text" name="search" placeholder="Search Recipe" aria-label="Search">
                        <button type="submit" name="submit"><i class="fa fa-search"></i></button>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <!-- End Content -->

    <!-- Start Category Card -->

    <section class="category">
        <div class="container py-5 text-center">
            <div class="row m-auto">
                <h1>Latest Recipes</h1>
            </div>
            <div class="row p-3 gap-0 row-gap-4 justify-content-center">
                <?php
                if (mysqli_num_rows($rows) > 0) {
                    while ($fetch_recipe = mysqli_fetch_assoc($rows)) {
                ?>
                        <div class="col-6 col-md-6 col-lg-2" onclick="location.href='recipe.php?recipe_id=<?php echo $fetch_recipe['recipe_id']; ?>'">
                            <div class="card text-center">
                                <!-- <form action="recipe.php"> -->
                                <img class="card-img-top" src="img/<?php echo $fetch_recipe['recipe_img']; ?>" alt="" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
                                <div class="card-body">
                                    <h6><?php echo $fetch_recipe['recipe_name']; ?></h6>
                                </div>
                                <!-- </form> -->
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p class="empty">No recipes found</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- End Category -->

    <!-- Start Upload Content -->

    <section class="upload">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 text-center">
                    <h1>Share Your<br>Amazing Recipe</h1>
                    <h5>Spread the love with your food</h5>
                    <button class="btn3 mt-3" type="submit" name="upload" onclick="handleUploadClick()">Upload Here</button>
                </div>
            </div>
        </div>
    </section>

    <!-- End Upload Content -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <script>
        function handleProfileClick() {
            <?php if (isset($_SESSION['username'])) { ?>
                // User is logged in, proceed to upload form
                window.location.href = 'profile.php';
            <?php } else { ?>
                // User is not logged in, redirect to sign in page
                alert('You must be logged to your account!');
                window.location.href = 'signin.php';

            <?php } ?>
        }


        function handleCategoryClick() {
            <?php if (isset($_SESSION['username'])) { ?>
                // User is logged in, redirect to category page
                window.location.href = 'category.php';
            <?php } else { ?>
                // User is not logged in, redirect to sign in page
                alert('You must be logged in to view categories!');
                window.location.href = 'signin.php';
            <?php } ?>
        }

        function handleSearchSubmit() {
            <?php if (isset($_SESSION['username'])) { ?>
                // User is logged in, allow form submission
                return true;
            <?php } else { ?>
                // User is not logged in, block form submission and redirect to login page
                alert('You must be logged in to use the search feature!');
                window.location.href = 'signin.php';
                return false;
            <?php } ?>
        }



        function handleUploadClick() {
            <?php if (isset($_SESSION['username'])) { ?>
                // User is logged in, proceed to upload form
                window.location.href = 'uploadform.php';
            <?php } else { ?>
                // User is not logged in, redirect to sign in page
                alert('You must be logged in to upload a recipe!');
                window.location.href = 'signin.php';

            <?php } ?>
        }

    

    </script>
</body>

</html>