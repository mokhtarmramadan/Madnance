<?php
include("connect.php");
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm'];

    // Check connection
    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }

    // Check if the username already exists
    $checkQuery = "SELECT * FROM users WHERE username = '$username'";
    $result = $connect->query($checkQuery);

    // Username and password validation
    if (!$username) {
        echo '<div class="alert alert-danger" role="alert">Missing Username</div>';
    } elseif ($result->num_rows > 0) {
        echo '<div class="alert alert-danger" role="alert">Username already exists. Please choose a different one.</div>';
    } elseif (!$password) {
        echo '<div class="alert alert-danger" role="alert">Missing Password</div>';
    } elseif (!$confirmPassword) {
        echo '<div class="alert alert-danger" role="alert">Confirm your password</div>';
    } elseif ($password != $confirmPassword) {
        echo '<div class="alert alert-danger" role="alert">Your password doesn\'t match the confirmation</div>';
    } elseif (strlen($password) < 8) {
        echo '<div class="alert alert-danger" role="alert">Password should be at least 8 characters</div>';
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $insertQuery = "INSERT INTO users (username, hash_password) VALUES ('$username', '$hashedPassword')";

        if ($connect->query($insertQuery) === TRUE) {
            // Get the user ID of the newly registered user
            $userIdQuery = "SELECT id FROM users WHERE username = '$username'";
            $userIdResult = $connect->query($userIdQuery);

            if ($userIdResult->num_rows > 0) {
                $row = $userIdResult->fetch_assoc();
                $userId = $row['id'];

                // Store user ID in the session
                $_SESSION['registration_success'] = true;
                $_SESSION['user_id'] = $userId;

                // Redirect to main.php
                header("Location: main.php");
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Error retrieving user ID.</div>';
            }
        } else {
            echo "Error: " . $insertQuery . "<br>" . $connect->error;
        }
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
                        <li class="nav-item"><a class="nav-link" href="login.php">Log In</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container mt-5">
            <div class="container">
                <h1 class="mb-4">Register now for a free trail!</h1>
                <form name="form" method="POST" action="register.php">
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

                    <div class="row mb-3">
                        <!-- Confirm Password input field -->
                        <label for="confirmPassword" class="col-sm-2 col-form-label">Confirm Password</label>
                        <div class="col-sm-10">
                            <input type="password" name="confirm" class="form-control" id="confirmPassword">
                        </div>
                    </div>

                    <!-- Register Button -->
                    <button type="submit" name="Register" style="background-color: yellow; color: black; border: none; padding: 10px 20px; font-size: 16px; font-weight: bold; cursor: pointer; border-radius: 10px;">Register</button>
                </form>
            </div>
        </main>
        <footer class="mb-5 small text-center text-muted my_footer">
            Created by <a style="text-decoration: none;" href="https://github.com/mokhtarmramadan">Mokhtar Ramadan</a>, 
            <a style="text-decoration: none;" href="https://github.com/ahmedadel1020">Ahmed Adel</a>, 
            <a style="text-decoration: none;" href="https://github.com/Eldemer">Ahmed El-Demerdash</a> and
            <a style="text-decoration: none;" href="https://github.com/sherfo">Mostafa Ashraf</a>
        </footer>

    </body>

</html>