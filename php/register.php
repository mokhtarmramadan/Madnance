<?php
include("connect.php");
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

 
    // Username and passowrd validation
    if(!$username) {
      echo "Missing Username";
    }
    else if ($result->num_rows > 0) {
      echo "Username already exists. Please choose a different one.";
    }
    else if(!$password) {
      echo "Missing Password";
    }
    else if(!$confirmPassword) {
      echo "confirm your password";
    }
    else if($password != $confirmPassword) {
      echo "Your password doesn't match the confirmation";
    }
    else if(strlen($password) < 8) {
      echo "Password should be at least 8 characters";
    }
    else {
      // Hash the password
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      // Insert new user into the database
      $insertQuery = "INSERT INTO users (username, hash_password) VALUES ('$username', '$hashedPassword')";

      if ($connect->query($insertQuery) === TRUE) {
          echo "Registration successful!";
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
                <a class="navbar-brand" href="/"><span class="green">$</span><span class="brown">M</span><span class="brown">A</span><span class="brown">D</span><span class="green">nance</span></a>
                <button aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-bs-target="#navbar" data-bs-toggle="collapse" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav ms-auto mt-2">
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Log In</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container mt-5">
          <form name="form" method="POST" action="register.php">
            <div class="row mb-3">
              <!-- Username input feild -->
              <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
              <div class="col-sm-10">
                <input type="text" name="username" class="form-control" id="inputEmail3">
              </div>
            </div>

            <div class="row mb-3">
              <!-- Password input feild  -->
              <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
              <div class="col-sm-10">
                <input type="password" name="password" class="form-control" id="inputPassword3">
              </div>
            </div>

            <div class="row mb-3">
              <!-- Confirm Password input feild  -->
              <label for="inputPassword3" class="col-sm-2 col-form-label">Confirm Password</label>
              <div class="col-sm-10">
                <input type="password" name="confirm" class="form-control" id="inputPassword3">
              </div>
            </div>
  
            <!-- Register Button  -->
            <button type="submit" name="Register" class="btn btn-primary">Register</button>
            </form>
        </main>

        <footer class="mb-5 small text-center text-muted">
            Data provided by <a href="https://iexcloud.io/">IEX</a>
        </footer>

    </body>

</html>