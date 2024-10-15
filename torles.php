<?php
require 'kapcsolat.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM cicak WHERE azonosito = $id";
    
    if (mysqli_query($dbconn, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Hiba történt a törlés során: " . mysqli_error($dbconn);
    }
} else {
    header("Location: index.php");
    exit();
}
?>