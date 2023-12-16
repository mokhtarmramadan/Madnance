<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, width=device-width">

        <!-- http://getbootstrap.com/docs/5.1/ -->
        <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" rel="stylesheet">
        <script crossorigin="anonymous" src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"></script>

        <!-- https://favicon.io/emoji-favicons/money-bag/ -->
        <link href="/static/favicon.ico" rel="icon">

        <link href="static/style.css" rel="stylesheet">

        <title>Madnance: Quote</title>

    </head>

    <body id="index_body">
        <nav class="bg-light border navbar navbar-expand-md navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="/"><span class="green">$</span><span class="brown">M</span><span class="brown">A</span><span class="brown">D</span><span class="green">nance</span></a>
                <button aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-bs-target="#navbar" data-bs-toggle="collapse" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav ms-auto mt-2">
                        <li class="nav-item"><a class="nav-link" href="php/register.php">Register</a></li>
                        <li class="nav-item"><a class="nav-link" href="php/login.php">Log In</a></li>
                    </ul>
                </div>
            </div>
        </nav>  
          
        <main class="container-fluid py-5 text-center">
        <h1>Product Search</h1>
           <form method="post" action="quote.php">
                <label for="symbol">symbol Name:</label>
                <input type="text" id="symbol" name="symbol" required>
                <button type="submit">Search</button>
          </form>
        </main>

        <footer class="mb-5 small text-center text-muted">
            Data provided by <a href="https://iexcloud.io/">IEX</a>
        </footer>

    </body>

</html>


<?php
    include("connect.php");

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "post") 
        // Get the search term from the form
        if(isset($_POST["symbol"])) {
        
            $symbol = $_POST["symbol"];
        }   

     if ($connect->connect_error) {
            die("Connection failed: " . $connect->connect_error);
        }

        $sql = "SELECT symbol, company , price FROM stocks WHERE symbol LIKE '%$symbol%'";
        $result = $connect->query($sql);

    
        // Display the results
        if ($result->num_rows > 0) {
            echo "<h2>Search Results:</h2>";
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>{$row['symbol']} - Price: {$row['price']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No results found for '$productName'.</p>";
        }

        // Close the database connection
        $connect->close();
    
    ?>

