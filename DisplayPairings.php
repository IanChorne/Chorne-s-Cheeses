<?php
//Database connection, hash later
$servername = "192.168.1.177"; //EchoBase
$username = "Ian";
$password = 'L!f31$G0od';
$dbName = "CheeseCellar";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        try {
            //Fetch rows with names of cheeses and wines by joining
            //Sources Used - https://www.w3schools.com/sql/sql_join.asp, https://www.cloudways.com/blog/how-to-join-two-tables-mysql/#left
            $query = "SELECT UserPairings.PairingID, UserPairings.UserPairingName, Cheeses.Name AS CheeseName, Wines.Name AS WineName, UserPairings.Side1, UserPairings.Side2, UserPairings.Side3
                FROM UserPairings
                LEFT JOIN Cheeses ON UserPairings.UserCheeseID = Cheeses.ID
                LEFT JOIN Wines ON UserPairings.UserWineID = Wines.WineID";

            $stmt = $conn->query($query);

            //Only wine that is displayed is Cabernet Sauvingnon, why?????
            if ($stmt->rowCount() > 0) {
                // Output data of each row
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "Pairing ID: " . $row["PairingID"] . " - Pairing Name: " . $row["UserPairingName"] . " - Cheese: " . $row["CheeseName"] . " - Wine: " . $row["WineName"] . " - Side1: " . $row["Side1"] . " - Side2: " . $row["Side2"] ." - Side3: " . $row["Side3"] ."<br>";
                }
            } else {
                echo "No pairings found.";
            }
        } catch (PDOException $e) {
            echo "Error fetching pairings: " . $e->getMessage();
        }
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cheeses</title>
</head>
<body>
<?php include('nav.php'); ?>
<form action="DisplayPairings.php" method="post">
    <button type="submit" name="submit">Display Pairings</button>
</form>
</body>
</html>
