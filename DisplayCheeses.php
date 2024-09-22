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
    $countryQuery = "SELECT DISTINCT Country FROM Cheeses";
    $countryResult = $conn->query($countryQuery);
    $countries = $countryResult->fetchAll(PDO::FETCH_COLUMN);

    //List Cheeses Alphabetically
    $query = "SELECT * FROM Cheeses ORDER BY Name ASC";
    $result = $conn->query($query);

    $stmt = [];

    //Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selectedListingOption = $_POST["listingOption"];

        if($selectedListingOption == "allCheeses"){
            $query = "SELECT * FROM Cheeses";
        }
        elseif ($selectedListingOption == "byCountry"){
            $selectedCountry = $_POST["country"];
            $selectedSorting = $_POST["sorting"];
            //Prepare and execute the query with user selections
            $query = "SELECT * FROM Cheeses WHERE Country = :country ORDER BY Name ";
            $query .= ($selectedSorting == 'AtoZ') ? 'ASC' : 'DESC';

            //Prepare - helps prevent Sql Injection
            //Source - https://www.w3schools.com/php/php_mysql_prepared_statements.asp
            $stmt = $conn->prepare($query);
            //Binding parameters makes sure the input is not treated as SQL Code
            $stmt->bindParam(':country', $selectedCountry);
            $stmt->execute();
        }

        //Check if $stmt is not an instance of PDOStatement
        if (!($stmt instanceof PDOStatement)) {
            $stmt = $conn->query("SELECT * FROM Cheeses");
        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "ID: " . $row["ID"] . " - Name: " . $row["Name"] . " - Country: " . $row["Country"] . "<br>";
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

<!--Just a page that lists paragraphs and images of the different cheeses in the table-->
<h1><b><i>Cheeses</i></b></h1>

<form action="DisplayCheeses.php" method="post">
    <!-- Dropdown for listing method -->
    <label for="listingOption">Listing Option:</label>
    <select name="listingOption" id="listingOption">
        <option value="allCheeses">All Cheeses</option>
        <option value="byCountry">By Country</option>
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
