<?php
$server = 'mysql';
$username = 'student';
$password = 'student';
$schema = 'sm_nursery_system';

try {
    $pdo = new PDO("mysql:host=$server;dbname=$schema", $username, $password);
}
catch(PDOException $e) {
    echo $e->getMessage();
}



/*
$server = 'localhost';
$username = 'root';
$password = '';
$schema = 'sm_nursery_system';
//Create Connection
$pdo = new PDO('mysql:dbname=' .$schema. ';host=' . $server, $username, $password);
*/

/*** mysql hostname 
$hostname = 'localhost';

/*** mysql username 
$username = 'root';

/*** mysql password 
$password = '';

$dbname='sm_nursery_system';

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }
 //active session userid***/
?>