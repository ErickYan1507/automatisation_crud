db.php

<?php

$dsn = "mysql:host=localhost;dbname=automatisation";
$username = "root";
$password = "";
$options = [];

try {
    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

?>
