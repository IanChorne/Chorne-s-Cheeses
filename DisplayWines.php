<?php
$servername = "192.168.1.177"; //EchoBase
$username = "Ian";
$password = 'L!f31$G0od';
$dbName = "CheeseCellar";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Finding countries to use for the drop-down menu
    //Source used: https://www.w3schools.com/sql/sql_distinct.asp
    $countryQuery = "SELECT DISTINCT Country FROM Wines";
    $countryResult = $conn->query($countryQuery);
    $countries = $countryResult->fetchAll(PDO::FETCH_COLUMN);

    //Finding colors to use for drop-down menu
    $colorQuery = "SELECT DISTINCT Color FROM Wines";
    $colorResult = $conn->query($colorQuery);
    $colors = $colorResult->fetchAll(PDO::FETCH_COLUMN);

    //List Wines Alphabetically
    $query = "SELECT * FROM Wines ORDER BY Name ASC";
    $result = $conn->query($query);

    $stmt = [];

    //Check if form is submitted
    if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
        $selectedListingOption = $_POST["listingOption"];

        if($selectedListingOption == "allWines"){
            $query = "SELECT * FROM Wines";
        }
        elseif ($selectedListingOption == "byCountry"){
            $selectedCountry = $_POST["country"];
            $selectedSorting = $_POST["sorting"];
            //Prepare and execute the query with user selections
            $query = "SELECT * FROM Wines WHERE Country = :country ORDER BY Name ";
            $query .= ($selectedSorting == 'AtoZ') ? 'ASC' : 'DESC';

            //Prepare - helps prevent Sql Injection
            //Source - https://www.w3schools.com/php/php_mysql_prepared_statements.asp
            $stmt = $conn->prepare($query);
            //Binding parameters makes sure the input is not treated as SQL Code
            $stmt->bindParam(':country', $selectedCountry);
            $stmt->execute();
        }
        elseif ($selectedListingOption == "byColor"){
            $selectedColor = $_POST["color"];
            $selectedSorting = $_POST["sorting"];
            //Prepare and execute the query with user selections
            $query = "SELECT * FROM Wines WHERE Color = :color";
            $query .= ($selectedSorting == 'AtoZ') ? 'ASC' : 'DESC';

            //Prepare - helps prevent Sql Injection
            //Source - https://www.w3schools.com/php/php_mysql_prepared_statements.asp
            $stmt = $conn->prepare($query);
            //Binding parameters makes sure the input is not treated as SQL Code
            $stmt->bindParam(':color', $selectedColor);
            /*$stmt->bindParam(':color', $selectedColor);
            $stmt->execute();*/
        }

        //Check if $stmt is not an instance of PDOStatement
        if (!($stmt instanceof PDOStatement)) {
            $stmt = $conn->query("SELECT * FROM Wines");
        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "Name: " . $row["Name"] . " - Country: " . $row["Country"] . " - Color: " . $row["Color"] . "<br>";
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
    <title>Wines</title>
</head>
<body>
<?php include('nav.php'); ?>

<!--Just a page that lists paragraphs and images of the different Wines in the table-->
<h1><b><i>Wines</i></b></h1>

<form action="DisplayWines.php" method="post">
    <!-- Dropdown for listing method -->
    <label for="listingOption">Listing Option:</label>
    <select name="listingOption" id="listingOption">
        <option value="allWines">All Wines</option>
        <option value="byCountry">By Country</option>
        <option value="byColor">By Color</option>
    </select>

    <!-- Dropdown for country -->
    <label for="country">Select Country:</label>
    <select name="country" id="country">
        <?php
        foreach ($countries as $country) {
            echo "<option value=\"$country\">$country</option>";
        }
        ?>
    </select>

    <!-- Dropdown for color -->
    <label for="country">Select Color:</label>
    <select name="color" id="color">
        <?php
        foreach ($colors as $color) {
            echo "<option value=\"$color\">$color</option>";
        }
        ?>
    </select>
    <!--Dropdown for sorting-->
    <label for="sorting">Sort By:</label>
    <select name="sorting" id="sorting">
        <option value="AtoZ">A-Z</option>
        <option value="ZtoA">Z-A</option>
    </select>

    <input type="submit" value="Submit">
</form>

</body>
</html>
