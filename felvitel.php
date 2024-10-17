<?php
require "kapcsolat.php";

$hibak = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Adatok tisztítása és formázása
    $cicaneve = trim(htmlspecialchars($_POST['cicaneve']));
    $cicafajta = trim(htmlspecialchars($_POST['cicafajta']));
    $cicakora = trim(htmlspecialchars($_POST['cicakora']));
    
    // Állat neve első betű nagybetűs, többi kisbetű
    $cicaneve = ucfirst(strtolower($cicaneve));
    
    // Fájl feltöltése
    $cicafoto = '';
    if (isset($_FILES['cicafoto']) && $_FILES['cicafoto']['error'] == 0) {
        // Max. fájlméret 2MB ellenőrzése
        if ($_FILES['cicafoto']['size'] > 2000000) {
            $hibak[] = "Túl nagy méretű képet töltöttél fel!";
        }

        // MIME-típus ellenőrzése
        $mime_tipusok = ['image/jpeg', 'image/gif', 'image/png'];
        if (!in_array($_FILES['cicafoto']['type'], $mime_tipusok)) {
            $hibak[] = "Nem megfelelő képformátum!";
        }

        // Ha nincs hiba, a fájl feldolgozása
        if (empty($hibak)) {
            $upload_dir = 'kepek/';
            $file_name = uniqid($_FILES['cicafoto']['name']);
            //var_dump($file_name);
            $target_file = $upload_dir . $file_name;
            
            // Ellenőrizzük, hogy valóban kép-e a fájl
            $check = getimagesize($_FILES['cicafoto']['tmp_name']);
            if ($check !== false) {
                if (move_uploaded_file($_FILES['cicafoto']['tmp_name'], $target_file)) {
                    $cicafoto = $file_name;
                } else {
                    $hibak[] = "Hiba történt a fájl feltöltésekor.";
                }
            } else {
                $hibak[] = "A fájl nem kép.";
            }
        }
    }

    // Adatok ellenőrzése
    if (empty($cicaneve)) {
        $hibak[] = "Nem adtad meg kedvenced nevét!";
    } elseif (strlen($cicaneve) < 2) {
        $hibak[] = "Biztos, hogy egy betű kedvenced neve?";
    }

    if (empty($cicafajta)) {
        $hibak[] = "Nem adtad meg kedvenced fajtáját!";
    }

    if (empty($cicakora)) {
        $hibak[] = "Nem adtad meg kedvenced korát!";
    }

    // Ha nincs hiba, adatbázisba beszúrás
    if (empty($hibak)) {
        $sql = "INSERT INTO cicak (cicaneve, cicafajta, cicakora, cicafoto) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $cicaneve, $cicafajta, $cicakora, $cicafoto);
        
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $hibak[] = "Hiba történt: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kedvencek felvitele</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container Felviter-Container mt-4">
    <h1>Kedvenc kisállat felvitele:</h1>

    <!-- Hibák megjelenítése számozatlan listában -->
    <?php if (!empty($hibak)): ?>
        <ul class="alert alert-danger">
            <?php foreach ($hibak as $hiba): ?>
                <li><?php echo $hiba; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
        <table class="table">
            <tr>
                <td><label for="cicaneve" class="form-label">Cica neve</label></td>
                <td><input type="text" class="form-control" id="cicaneve" name="cicaneve" required></td>
            </tr>
            <tr>
                <td><label for="cicafajta" class="form-label">Cica fajtája</label></td>
                <td><input type="text" class="form-control" id="cicafajta" name="cicafajta" required></td>
            </tr>
            <tr>
                <td><label for="cicakora" class="form-label">Cica kora (hetes/éves)</label></td>
                <td><input type="number" class="form-control" id="cicakora" name="cicakora" required></td>
            </tr>
            <tr>
                <td><label for="cicafoto" class="form-label">Cica fényképe</label></td>
                <td><input type="file" class="form-control" id="cicafoto" name="cicafoto" accept="image/jpeg, image/gif, image/png" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" class="btn btn-primary">Hozzáadás</button>
                    <button type="reset" class="btn btn-warning">Mégse</button>
                    <a href='index.php' class='btn btn-secondary'>Vissza a főoldalra</a>
                </td>
            </tr>
        </table>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
