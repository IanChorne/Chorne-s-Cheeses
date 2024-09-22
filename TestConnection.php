<?php
phpinfo();
//Database connection, hash later
$servername = "192.168.1.177"; //EchoBase
$username = "Ian";
$password = 'L!f31$G0od';
$dbName = "CheeseCellar";

$conn = mysqli_connect($servername, $username, $password, $dbName);

if (mysqli_connect_errno()) {
    echo "Connection failed: " . mysqli_connect_error();
    exit();
}