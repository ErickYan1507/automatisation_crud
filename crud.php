<?php
include 'db.php';

$table_name = isset($_GET['table']) ? $_GET['table'] : null;

if ($table_name) {
    try {
        // Préparer et exécuter la requête pour décrire la table
        $stmt = $conn->query("DESCRIBE $table_name");
        $columns = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $columns[] = $row['Field'];
        }

        // Formulaire d'insertion avec padding
        echo "<div class='container p-4'>"; // Ajout de padding autour du formulaire
        echo "<h2 class='pb-3'>Ajouter un enregistrement dans la table $table_name</h2>"; // Ajout de padding bas pour espacement
        echo "<form action='crud_action.php?table=$table_name&action=insert' method='POST'>";
        foreach ($columns as $column) {
            if ($column != 'id') {
                echo "<div class='mb-3'>"; // Ajout de margin-bottom pour espacement vertical entre les inputs
                echo "<label class='form-label'>$column :</label>";
                echo "<input type='text' class='form-control' name='$column' required>";
                echo "</div>";
            }
        }
        echo "<button type='submit' class='btn btn-primary'>Ajouter</button>";
        echo "</form>";
        echo "</div>"; // Fin du container pour le formulaire

        // Affichage des enregistrements avec padding
        echo "<div class='container p-4'>"; // Ajout de padding autour du tableau
        echo "<h2 class='pb-3'>Liste des enregistrements dans $table_name</h2>";
        echo "<table class='table table-bordered table-striped'>"; // Utilisation des classes Bootstrap pour styliser le tableau
        echo "<thead>";
        echo "<tr>";
        foreach ($columns as $column) {
            echo "<th>$column</th>";
        }
        echo "<th>Actions</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Préparer et exécuter la requête pour sélectionner toutes les lignes
        $stmt = $conn->query("SELECT * FROM $table_name");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($columns as $column) {
                echo "<td>" . htmlspecialchars($row[$column]) . "</td>";
            }
            echo "<td>
                <a href='crud.php?table=$table_name&id=".$row['id']."&action=edit' class='btn btn-warning btn-sm'>Modifier</a>
                <a href='crud_action.php?table=$table_name&id=".$row['id']."&action=delete' class='btn btn-danger btn-sm'>Supprimer</a>
            </td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>"; // Fin du container pour le tableau

    } catch (PDOException $e) {
        echo "<div class='alert alert-danger p-4'>Erreur: " . $e->getMessage() . "</div>";
    }
}
?>

<!-- Inclusion de Bootstrap -->
<link rel="stylesheet" href="./bootstrap/css/bootstrap.css">
<script src="./bootstrap/bootstrap.js"></script>
