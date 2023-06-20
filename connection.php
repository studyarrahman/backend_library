
<?php
// connection.php
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

function getConnection(): PDO
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "library";
    return new PDO("mysql:host=$servername;dbname=$database", $username, $password, [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION]);
}