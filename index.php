<?php
//https://www.mysqltutorial.org/mysql-cheat-sheet.aspx
//Database connection, hash later
$servername = "192.168.1.177"; //EchoBase
$username = "Ian";
$password = 'L!f31$G0od';
$dbName = "CheeseCellar";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chorne's Cheeses</title>
</head>
<body>
<?php include('nav.php'); ?>
<!--<div class="topnotch">
    <a class="active" href="#index.html">Home</a>
    <a href="#Cheeses.html">Cheese</a>
    <a href="#Wines.html">Wine</a>
    <a href="Sides.html">Sides</a>
    <a href="#Pairings">Pairings</a>
    <a href="#UserPairings.html">My Profile</a>
</div>-->
<h1><b>Chorne's Cheeses</b></h1>

<h3><i><b>Welcome!</b></i></h3>

<p>This website is here for you to learn about different types of cheese and wine, such as their country of origin, color, history, etc.</p>

<h2>Pair of the Day</h2>
<h3><i><b>Classic Italy</b></i></h3>
<p>Creamy Mozzarella, paired with tomato and basil on a dried, toasted baguette slice. </p>

</body>
</html>
