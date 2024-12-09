<?php

include 'config.php';
session_start();

if (isset($_POST['submit'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

    $select = mysqli_query($conn, "SELECT * FROM `user` WHERE email = '$email' AND password = '$pass'") or die('query failed');

    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['login'] = true;
        header('location:index.php');
    } else {
        $message = "incorrect password or email!";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="css/signin.css">
    <link rel="stylesheet" href="css/signin.css?v=1.0">

</head>

<body>

    <section class="Form my-4 mx-5">
        <div class="container">
            <div class="row g-0">
                <div class="col-lg-5">
                    <img src="asset/poster1.png" class="img-fluid" alt="">
                </div>
                <div class="col-lg-7 px-5 pt-5 justify-content-center align-items-center flex-column">
                    <h1 class="logo"><img src="asset/ThePinkPantry.png" class="center"></h1>
                    <h4>Sign into your account</h4>
                    <form action="" method="post">
                        <div class="form-row">
                            <div class="col-lg-7">
                                <input type="email" name="email" class="form-control my-1 p-3" placeholder="Email Address" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-7">
                                <input type="password" name="password" class="form-control my-1 p-3" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-7">
                                <input type="submit" name="submit" class="btn1 mt-3 my-1" value="Sign In">
                            </div>
                        </div>
                        <p>Don't have an account?
                            <a href="signup.php">Register here</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>