<?php
    header('Content-Type: charset=utf8');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); 
define('DB_NAME', 'cicak');

// 5. Kapcsolat létrehozása az adatbázissal
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 6. Kapcsolat ellenőrzése
/*
if ($conn->connect_error) {
    die('Hiba az adatbázis csatlakozásakor: ' . $conn->connect_error);
} else {
    echo 'Sikeres kapcsolódás';
}


if ($conn->connect_error) {
    die('Hiba az adatbázis csatlakozásakor: ' . $conn->connect_error);
} else {
    echo 'Sikeres kapcsolódás';
}
*/


?>
