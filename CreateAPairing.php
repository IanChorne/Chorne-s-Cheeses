<?php
//phpinfo();
//Database connection, hash later
$servername = "192.168.1.177"; //EchoBase
$username = "Ian";
$password = 'L!f31$G0od';
$dbName = "CheeseCellar";

try {
    //Source used: https://www.dummies.com/article/technology/programming-web-design/general-programming-web-design/php-mysql-javascript-one-dummies-cheat-sheet-249477/
    $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $cheeseSelectStr = "";
    $query = "SELECT * FROM Cheeses";
    $cheeseResult = $conn->query($query);

    while ($row = $cheeseResult->fetch(PDO::FETCH_ASSOC)) {
        $cheeseSelectStr .= "<OPTION VALUE=\"{$row['ID']}\" >{$row['Name']}\n";
    }

    $wineSelectStr = "";
    $query = "SELECT * FROM Wines";
    $wineResult = $conn->query($query);

    while ($row = $wineResult->fetch(PDO::FETCH_ASSOC)) {
        $wineSelectStr .= "<OPTION VALUE=\"{$row['WineID']}\" >{$row['Name']}\n";
    }

    $pairingSelectStr = "";
    $query = "SELECT * FROM UserPairings";
    $PairingResult = $conn->query($query);

    // Add Query to select pairings with different country of origins
    // Example: SELECT * FROM UserPairings WHERE Cheese.Country = Italy
    // Figure out how to organize better, don't want to be repetitive

    //Function to insert the created pairing into MySql
    function addPairing($conn, $userPairingName, $userCheeseID, $userWineID, $userSide1, $userSide2, $userSide3) {
        try {
            $sql = "INSERT INTO UserPairings (UserPairingName, UserCheeseID, UserWineID, Side1, Side2, Side3) 
                VALUES (:userPairingName, :userCheeseID, :userWineID, :userSide1, :userSide2, :userSide3)";
            $stmt = $conn->prepare($sql);
            //Table may be too big, might remove the option for sides later
            //$stmt->bindParam(':userID', $userID);
            $stmt->bindParam(':userPairingName', $userPairingName);
            $stmt->bindParam(':userCheeseID', $userCheeseID);
            $stmt->bindParam(':userWineID', $userWineID);
            $stmt->bindParam(':userSide1', $userSide1);
            $stmt->bindParam(':userSide2', $userSide2);
            $stmt->bindParam(':userSide3', $userSide3);

            $stmt->execute();
            echo "Pairing added successfully!";
        } catch (PDOException $e) {
            echo "Error adding pairing: " . $e->getMessage();
        }
    }

    if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
        $userPairingName = $_POST["userPairingName"];
        $userCheeseName = $_POST["cheese"][0];
        $userWineName = $_POST["wine"][0];
        $userSide1 = $_POST["userSide1"];
        $userSide2 = $_POST["userSide2"];
        $userSide3 = $_POST["userSide3"];

        addPairing($conn, $userPairingName, $userCheeseName, $userWineName, $userSide1, $userSide2, $userSide3);
    }


} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create a Pairing</title>
</head>
<body>
<?php include('nav.php'); ?>


<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
    <!--<label for="userID">User ID</label>
    <label>
        <input type="text" name="userID" id="userID" required>
    </label>-->
    <label for="userPairingName">Pairing Name:</label>
    <label>
        <input type="text" name="userPairingName" id="userPairingName" required>
    </label>


    <label for="userCheese">Cheese:</label>
    <select name="cheese[]" id="userCheese">
        <?php echo "$cheeseSelectStr"?>
    </select>

    <label for="userWine">Wine:</label>
    <select name="wine" id="userWine">
        <?php echo "$wineSelectStr"?>
    </select>

    <label for="userSide1">Side 1:</label>
    <label>
        <input type="text" name="userSide1" id="userSide1">
    </label>

    <label for="userSide2">Side 2:</label>
    <label>
        <input type="text" name="userSide2" id="userSide2">
    </label>

    <label for="userSide3">Side 3:</label>
    <label>
        <input type="text" name="userSide3" id="userSide3">
    </label>

    <!--<input type="submit" name="submit" value="Create Pairing">-->
    <br>
    <button type="submit" name="submit">Submit</button>
</form>
</body>
</html>
