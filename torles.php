<?php
require 'kapcsolat.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM cicak WHERE azonosito = $id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Hiba történt a törlés során: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
    exit();
}
?>