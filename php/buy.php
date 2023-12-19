<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate input
    $symbol = trim($_POST["symbol"]);
    $shares = intval($_POST["shares"]);

    // Validate symbol existence in the stocks database
    $symbolCheckQuery = "SELECT * FROM stocks WHERE symbol = '$symbol'";
    $symbolCheckResult = $connect->query($symbolCheckQuery);

    if ($symbolCheckResult->num_rows === 0) {
        // Symbol does not exist
        echo '<div class="alert alert-danger" role="alert">Invalid symbol. Please enter a valid stock symbol.</div>';
    } elseif ($shares <= 0) {
        // Invalid number of shares
        echo '<div class="alert alert-danger" role="alert">Invalid number of shares. Please enter a positive integer greater than 0.</div>';
    } else {
        // Fetch stock price from the database
        $row = $symbolCheckResult->fetch_assoc();
        $stockPrice = $row['price'];

        // Calculate total price
        $totalPrice = $shares * $stockPrice;

        // Get user's cash from the database
        $userId = $_SESSION['user_id'];
        $getUserQuery = "SELECT cash FROM users WHERE id = '$userId'";
        $getUserResult = $connect->query($getUserQuery);

        if ($getUserResult->num_rows > 0) {
            $userRow = $getUserResult->fetch_assoc();
            $userCash = $userRow['cash'];

            // Check if the user has enough cash to buy
            if ($userCash >= $totalPrice) {
                // Check if the user already owns the stock
                $checkStockQuery = "SELECT * FROM transactions WHERE user_id = '$userId' AND symbol = '$symbol'";
                $checkStockResult = $connect->query($checkStockQuery);

                if ($checkStockResult->num_rows > 0) {
                    // User already owns the stock, update the shares
                    $updateSharesQuery = "UPDATE transactions SET shares = shares + $shares WHERE user_id = '$userId' AND symbol = '$symbol'";
                    $connect->query($updateSharesQuery);
                } else {
                    // User doesn't own the stock, insert a new entry
                    $insertQuery = "INSERT INTO transactions (user_id, symbol, shares, price, date) VALUES ('$userId', '$symbol', $shares, $stockPrice, NOW())";
                    $connect->query($insertQuery);
                }

                // Update user's cash in the database
                $newCash = $userCash - $totalPrice;
                $updateCashQuery = "UPDATE users SET cash = $newCash WHERE id = '$userId'";
                $connect->query($updateCashQuery);

                // Insert data into the history table
                $insertHistoryQuery = "INSERT INTO history (user_id, symbol, shares, price, date) VALUES ('$userId', '$symbol', $shares, $stockPrice, NOW())";
                $connect->query($insertHistoryQuery);

                // Redirect to main.php with a success message
                $_SESSION['buy_success'] = true;
                header("Location: main.php");
                exit();
            } else {
                // User doesn't have enough cash to buy
                echo '<div class="alert alert-danger" role="alert">You\'ve exceeded the $10,000 free trial threshold, Please recharge and try again.</div>';
            }
        } else {
            // Error retrieving user's cash
            echo '<div class="alert alert-danger" role="alert">Error retrieving user information. Please try again.</div>';
        }
    }
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

        <title>Madnance: Buy</title>

    </head>

    <body id="index_body">
        <nav class="bg-light border navbar navbar-expand-md navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="main.php"><span style="color: green;">$</span><span style="color: black;">M</span><span style="color: black;">A</span><span style="color: black;">D</span><span style="color: green;">nance</span></a>
                <button aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-bs-target="#navbar" data-bs-toggle="collapse" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav me-auto mt-2">
                                <li class="nav-item"><a class="nav-link" href="quote.php">Quote</a></li>
                                <li class="nav-item"><a class="nav-link" href="buy.php">Buy</a></li>
                                <li class="nav-item"><a class="nav-link" href="sell.php">Sell</a></li>
                                <li class="nav-item"><a class="nav-link" href="history.php">History</a></li>
                    </ul>
                    <ul class="navbar-nav ms-auto mt-2">
                        <li class="nav-item"><a class="nav-link" href="../index.html">Log out</a></li>
                    </ul>
                </div>
            </div>
        </nav>  
          
        <main class="container-fluid py-5 text-center">
            <div class="container">
                <h1 class="mb-4">Buy</h1>
                <form action="buy.php" method="post" class="d-flex flex-column align-items-center">
                    <div class="mb-3">
                        <input autocomplete="off" autofocus class="form-control" name="symbol" placeholder="Enter Symbol"
                            type="text">
                    </div>
                    <div class="mb-3">
                        <input autocomplete="off" autofocus class="form-control" name="shares" placeholder="Enter Shares"
                            type="number" min="1">
                    </div>
                    <button style="background-color: #67B267; color: black; border: none; padding: 10px 20px; font-size: 16px; font-weight: bold; cursor: pointer; border-radius: 10px;" type="submit">Buy</button>
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