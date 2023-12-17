<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate input
    $symbol = $_POST["symbol"];
    $shares = intval($_POST["shares"]);

    // Validate input
    if (!$symbol) {
        echo '<div class="alert alert-danger" role="alert">Missing stock name.</div>';
    }
    else if ($shares < 1) {
        echo '<div class="alert alert-danger" role="alert">Invalid number of shares. Please enter a positive integer greater than 0.</div>';
    } else {
        // Fetch stock details from the database
        $userId = $_SESSION['user_id'];
        $fetchStockQuery = "SELECT * FROM transactions WHERE user_id = '$userId' AND symbol = '$symbol' AND shares > 0";
        $fetchStockResult = $connect->query($fetchStockQuery);

        if ($fetchStockResult->num_rows > 0) {
            $row = $fetchStockResult->fetch_assoc();
            $stockShares = $row['shares'];
            $stockPrice = $row['price'];

            // Check if the user has enough shares to sell
            if ($shares <= $stockShares) {
                // Calculate total price
                $totalPrice = $shares * $stockPrice;

                // Update user's cash in the database
                $getUserQuery = "SELECT cash FROM users WHERE id = '$userId'";
                $getUserResult = $connect->query($getUserQuery);

                if ($getUserResult->num_rows > 0) {
                    $userRow = $getUserResult->fetch_assoc();
                    $userCash = $userRow['cash'];

                    // Update user's cash
                    $newCash = $userCash + $totalPrice;
                    $updateCashQuery = "UPDATE users SET cash = $newCash WHERE id = '$userId'";
                    $connect->query($updateCashQuery);

                    // Update the shares in the transactions table
                    $updateSharesQuery = "UPDATE transactions SET shares = shares - $shares WHERE user_id = '$userId' AND symbol = '$symbol'";
                    $connect->query($updateSharesQuery);

                    // Insert data into the history table with negative shares
                    $insertQuery = "INSERT INTO history (user_id, symbol, shares, price, date) VALUES ('$userId', '$symbol', -$shares, $totalPrice, NOW())";

                    if ($connect->query($insertQuery) === TRUE) {
                        // Redirect to main.php with a success message
                        $_SESSION['sell_success'] = true;
                        header("Location: main.php");
                        exit();
                    } else {
                        // Display an error message if the insertion fails
                        echo '<div class="alert alert-danger" role="alert">Error processing the request. Please try again.</div>';
                    }
                } else {
                    // Error retrieving user's cash
                    echo '<div class="alert alert-danger" role="alert">Error retrieving user information. Please try again.</div>';
                }
            } else {
                // Not enough shares to sell
                echo '<div class="alert alert-danger" role="alert">Not enough shares to sell. Please enter a valid number of shares.</div>';
            }
        } else {
            // No stocks found for the selected symbol
            echo '<div class="alert alert-danger" role="alert">No stocks found for the selected symbol.</div>';
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

        <title>Madnance: Sell</title>

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
                <h1 class="mb-4">Sell</h1>
                <form action="sell.php" method="post" class="d-flex flex-column align-items-center">
                    <div class="mb-3">
                        <select name="symbol" class="form-select">
                            <?php
                            $userId = $_SESSION['user_id'];
                            $fetchStocksQuery = "SELECT DISTINCT symbol FROM transactions WHERE user_id = '$userId' AND shares > 0";
                            $fetchStocksResult = $connect->query($fetchStocksQuery);

                            while ($row = $fetchStocksResult->fetch_assoc()) {
                                echo '<option value="' . $row['symbol'] . '">' . $row['symbol'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input autocomplete="off" autofocus class="form-control" name="shares" placeholder="Enter Shares"
                            type="number" min="1">
                    </div>
                    <button style="background-color: #67B267; color: black; border: none; padding: 10px 20px; font-size: 16px; font-weight: bold; cursor: pointer; border-radius: 10px;" type="submit">Sell</button>
                </form>
            </div>
        </main>
        <footer class="mb-5 small text-center text-muted">
            Data provided by <a href="https://iexcloud.io/">IEX</a>
        </footer>

    </body>

</html>