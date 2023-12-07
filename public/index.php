<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madnance</title>
</head>
<body>
    <form method="post">
        <label>Username</label>
        <input type= "text" name = "username"><br>
        <label>Password</label>
        <input type="password" name = "password"><br>
        <input type="submit" value="Log in">
    </form>
</body>
</html>
<?php 
$username=$_POST["username"];
$password=$_POST["password"];
echo "{$username} <br>";
echo "{$password} <br>";
?>
