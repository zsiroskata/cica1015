<?php
require "kapcsolat.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cicaneve = $_POST['cicaneve'];
    $cicafajta = $_POST['cicafajta'];
    $cicakora = $_POST['cicakora'];
    $cicafoto = '';

    if (isset($_FILES['cicafoto']) && $_FILES['cicafoto']['error'] == 0) {
        $upload_dir = 'kepek/';
        $file_name = basename($_FILES['cicafoto']['name']);
        $target_file = $upload_dir . $file_name;
        
        $check = getimagesize($_FILES['cicafoto']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['cicafoto']['tmp_name'], $target_file)) {
                $cicafoto = $file_name;
            } else {
                echo "Hiba történt a fájl feltöltésekor.";
            }
        } else {
            echo "A fájl nem kép.";
        }
    }

    if (!empty($cicaneve) && !empty($cicafajta) && !empty($cicakora) && !empty($cicafoto)) {
        $sql = "INSERT INTO cicak (cicaneve, cicafajta, cicakora, cicafoto) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $cicaneve, $cicafajta, $cicakora, $cicafoto);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Sikeresen hozzáadva!</div>";
        } else {
            echo "<div class='alert alert-danger'>Hiba történt: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-warning'>Minden mezőt ki kell tölteni!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cica hozzáadása</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Cica hozzáadása</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="cicaneve" class="form-label">Cica neve</label>
            <input type="text" class="form-control" id="cicaneve" name="cicaneve" required>
        </div>
        <div class="mb-3">
            <label for="cicafajta" class="form-label">Cica fajtája</label>
            <input type="text" class="form-control" id="cicafajta" name="cicafajta" required>
        </div>
        <div class="mb-3">
            <label for="cicakora" class="form-label">Cica kora</label>
            <input type="number" class="form-control" id="cicakora" name="cicakora" required>
        </div>
        <div class="mb-3">
            <label for="cicafoto" class="form-label">Cica fényképe</label>
            <input type="file" class="form-control" id="cicafoto" name="cicafoto" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Hozzáadás</button>
        <a href='index.php' class='btn btn-secondary'>mégse</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
