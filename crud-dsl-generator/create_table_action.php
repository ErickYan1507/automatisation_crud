<?php
include 'db.php';  // Inclure le fichier de connexion PDO

$message = ''; // Variable pour stocker le message de retour

if (isset($_POST['table_name']) && isset($_POST['column_name']) && isset($_POST['data_type'])) {
    $table_name = $_POST['table_name'];
    $columns = $_POST['column_name'];
    $data_types = $_POST['data_type'];

    // Construire la requête SQL pour créer la table
    $query = "CREATE TABLE $table_name (id INT(11) AUTO_INCREMENT PRIMARY KEY, ";

    for ($i = 0; $i < count($columns); $i++) {
        $query .= $columns[$i] . " " . $data_types[$i];
        if ($i < count($columns) - 1) {
            $query .= ", ";
        }
    }

    $query .= ");";

    try {
        // Exécuter la requête
        $conn->exec($query);
        $message = "Table $table_name créée avec succès.";
    } catch (PDOException $e) {
        $message = "Erreur lors de la création de la table: " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;

    // Rediriger vers la page avec le message
    header("Location: create_table.php?message=" . urlencode($message));
    exit();
}
?>
