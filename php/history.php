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

        <title>Madnance: History</title>

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
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Symbols</th>
                        <th scope="col">Shares</th>
                        <th scope="col">Price</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    session_start();
                    include("connect.php");

                    // Get user ID from session
                    $userId = $_SESSION['user_id'];

                    // Fetch transaction history for the user
                    $fetchHistoryQuery = "SELECT * FROM history WHERE user_id = '$userId'";
                    $fetchHistoryResult = $connect->query($fetchHistoryQuery);

                    // Loop through results and display in the table
                    while ($row = $fetchHistoryResult->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['symbol'] . '</td>';
                        echo '<td>' . $row['shares'] . '</td>';
                        echo '<td>' . $row['price'] . '</td>';
                        echo '<td>' . $row['date'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </main>
        </main>
        <footer class="mb-5 small text-center text-muted">
            Data provided by <a href="https://iexcloud.io/">IEX</a>
        </footer>

    </body>

</html>