<?php
include 'db.php';

// Récupérer toutes les tables dans la base de données
$query = "SHOW TABLES";
$tables = $conn->query($query)->fetchAll(PDO::FETCH_COLUMN);

$conn = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Tables</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.css">
    <style>
        ul li a{
            text-decoration:none;
            color:rgb(56, 53, 53);
        }
        ul li a:hover{
            color:red;
            font-weight:bolder;
        }
    </style>
</head>
<body>

<h2>Liste des Tables</h2>
<ul class="row">
    <?php foreach ($tables as $table): ?>
        <li class="col-md-4 col-sm-4"><a href="crud.php?table=<?php echo urlencode($table); ?>"><?php echo htmlspecialchars($table); ?></a></li>
    <?php endforeach; ?>
</ul>

<a href="create_table.php">Créer une nouvelle table</a>
<script src="./bootstrap/js/bootstrap.js"></script>
</body>
</html>
