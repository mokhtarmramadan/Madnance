<?php
// Start the session at the beginning of the main.php page
session_start();

// Check if the session variable is set
if (isset($_SESSION['registration_success']) && $_SESSION['registration_success'] === true) {
    echo '<div class="alert alert-success" role="alert">Registration successful!</div>';

    // Unset the session variable to avoid displaying the message again on page reload
    unset($_SESSION['registration_success']);
}
else if(isset($_SESSION['login_session']) && $_SESSION['login_session'] === true) {
    echo '<div class="alert alert-success" role="alert">Login successful!</div>';
    unset($_SESSION['login_session']);
}
else if(isset($_SESSION['buy_success']) && $_SESSION['buy_success'] === true) {
    echo '<div class="alert alert-success" role="alert">Bought successfully!</div>';
    unset($_SESSION['buy_success']);
}
else if(isset($_SESSION['sell_success']) && $_SESSION['sell_success']) {
    echo '<div class="alert alert-success" role="alert">Sold successfully!</div>';
    unset($_SESSION['sell_success']);
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

        <title>Madnance: Main Page</title>

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
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Symbols</th>
                            <th scope="col">Shares</th>
                            <th scope="col">Share price</th>
                            <th scope="col">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("connect.php");

                        $userId = $_SESSION['user_id'];

                        // Fetch user's transaction history
                        $fetchTransactionsQuery = "SELECT * FROM transactions WHERE user_id = '$userId'";
                        $fetchTransactionsResult = $connect->query($fetchTransactionsQuery);

                        // Fetch user's cash amount
                        $fetchCashQuery = "SELECT cash FROM users WHERE id = '$userId'";
                        $fetchCashResult = $connect->query($fetchCashQuery);
                        $userData = $fetchCashResult->fetch_assoc();
                        $cash_left = $userData['cash'];
                        $totalCash = 0; // Initialize total cash

                        // Loop through results and display in the table
                        while ($row = $fetchTransactionsResult->fetch_assoc()) {
                            if($row['shares'] > 0) {
                                echo '<tr>';
                                echo '<td>' . $row['symbol'] . '</td>';
                                echo '<td>' . $row['shares'] . '</td>';
                                echo '<td>' . '$' . number_format($row['price'], 2) . '</td>';
                                echo '<td>' . '$' . number_format($row['shares'] * $row['price'], 2) . '</td>';
                                echo '</tr>';

                                $totalCash += ($row['shares'] * $row['price']); // Update total cash
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <th scope="1">Total Amount</th>
                            <th scope="1"><mark><?php echo '$' . number_format($totalCash, 2); ?></mark></th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <th scope="1">Cash Left</th>
                            <th scope="1"> <mark><?php echo '$' . number_format($cash_left, 2); ?></mark></th>
                        </tr>
                    </tfoot>
                </table>
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