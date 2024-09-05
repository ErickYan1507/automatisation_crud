<?php
include 'db.php';

$table_name = $_GET['table'];
$action = $_GET['action'];

try {
    if ($action == 'insert') {
        $columns = [];
        $values = [];
        $placeholders = [];

        foreach ($_POST as $column => $value) {
            $columns[] = $column;
            $placeholders[] = ":$column";
            $values[":$column"] = $value;
        }

        $query = "INSERT INTO $table_name (" . implode(",", $columns) . ") VALUES (" . implode(",", $placeholders) . ")";
        
        $stmt = $conn->prepare($query);
        if ($stmt->execute($values)) {
            echo "Nouvel enregistrement ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout de l'enregistrement.";
        }
    } elseif ($action == 'delete') {
        $id = $_GET['id'];
        $query = "DELETE FROM $table_name WHERE id = :id";
        
        $stmt = $conn->prepare($query);
        if ($stmt->execute([':id' => $id])) {
            echo "Enregistrement supprimé avec succès.";
        } else {
            echo "Erreur lors de la suppression de l'enregistrement.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}

// Il n'est pas nécessaire de fermer explicitement la connexion avec PDO, car elle se fermera automatiquement à la fin du script.
