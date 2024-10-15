<?php
require "kapcsolat.php";

$kimenet = '';
if (isset($_POST['kifejezes'])) {
    $keresendo = $_POST['kifejezes'];
    $sql = "SELECT * FROM cicak WHERE cicaneve LIKE '%$keresendo%' OR cicafajta LIKE '%$keresendo%'";
} else {
    $sql = "SELECT * FROM cicak";
}

$result = $conn->query($sql);


if (@mysqli_num_rows($result) < 1) {
    $kimenet = "<article>
    <h2>Nincs ilyen találat a rendszerben!</h2>
    </article>\n";
} else {
    $kimenet = "";
    while ($sor = $result->fetch_assoc()) {
        $kimenet .= "
        <div class='col-lg-4 col-md-6 col-sm-12'>
            <div class='card mb-4'>
                <img src='kepek/{$sor['cicafoto']}' class='card-img-top' alt='{$sor['cicaneve']}'>
                <div class='card-body'>
                    <h5 class='card-title'>{$sor['cicaneve']}</h5>
                    <h6 class='card-subtitle mb-2 text-muted'>Fajta: {$sor['cicafajta']}</h6>
                    <p class='card-text'>Kor: {$sor['cicakora']} év</p>
                    <a href='felvitel.php?id={$sor['azonosito']}' class='btn btn-success'>Hozzáadás</a>
                    <a href='torles.php?id={$sor['azonosito']}' class='btn btn-danger'>Törlés</a>
                </div>
            </div>
        </div>\n";
    }
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cicák</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<!-- nav -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Cicák <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Kapcsolat</a>
      </li>
    </ul>

    <form class="form-inline my-2 my-lg-0 ml-auto" method="post">
      <input class="form-control mr-sm-2" type="search" id="kifejezes" name="kifejezes" placeholder="Keresés" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Keresés</button>
    </form>
  </div>
</nav>


<!-- kártyák-->
<div class="container mt-4">
    <div class="row justify-content-md-center">
      
        <?php print $kimenet; ?>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
