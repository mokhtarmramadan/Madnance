<?php
session_start();
include("connect.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sign'])) {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check connection
    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }

    // Check if the username exists
    $checkQuery = "SELECT * FROM users WHERE username = '$username'";
    $result = $connect->query($checkQuery);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['hash_password'];

        if (password_verify($password, $hashedPassword)) {
            // Get the user ID
            $userId = $row['id'];

            // Store user ID in the session
            $_SESSION['login_session'] = true;
            $_SESSION['user_id'] = $userId;

            header("Location: main.php");
            exit();
        } else {
            // Password is incorrect
            echo '
            <div class="alert alert-danger" role="alert">
            Incorrect password. Please try again.</div>';
        }
    } else {
        // Username doesn't exist
        echo '<div class="alert alert-danger" role="alert">Username not found. Please check your username and try again.</div>';
    }

    // Close the database connection
    $connect->close();
}
?>



<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, width=device-width">

        <!-- http://getbootstrap.com/docs/5.1/ -->
        <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" rel="stylesheet">
        <script crossorigin="anonymous" src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"></script>

        <!-- https://favicon.io/emoji-favicons/money-bag/ -->
        <link href="../static/favicon.ico" rel="icon">

        <link href="../static/style.css" rel="stylesheet">

        <title>Madnance</title>

    </head>

    <body>

        <nav class="bg-light border navbar navbar-expand-md navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.html"><span class="green">$</span><span class="brown">M</span><span class="brown">A</span><span class="brown">D</span><span class="green">nance</span></a>
                <button aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-bs-target="#navbar" data-bs-toggle="collapse" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav ms-auto mt-2">
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container py-5 text-center">
            <div class="container">
                <h1 class="mb-4">Login now!</h1>
                <form name="form" method="POST" action="login.php">
                    <div class="row mb-3">
                        <!-- Username input field -->
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" name="username" class="form-control" id="inputEmail3">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Password input field -->
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" name="password" class="form-control" id="inputPassword3">
                        </div>
                    </div>

                    <!-- Sign in Button -->
                    <button type="submit" name="sign" style="background-color: yellow; color: black; border: none; padding: 10px 20px; font-size: 16px; font-weight: bold; cursor: pointer; border-radius: 10px;">Login</button>
                </form>
            </div>
        </main>

        <footer class="mb-5 small text-center text-muted">
            Data provided by <a href="https://iexcloud.io/">IEX</a>
        </footer>

    </body>

</html>