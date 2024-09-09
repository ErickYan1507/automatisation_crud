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

        // Formulaire d'insertion
        echo "<h2>Ajouter un enregistrement dans la table $table_name</h2>";
        echo "<form action='crud_action.php?table=$table_name&action=insert' method='POST'>";
        foreach ($columns as $column) {
            if ($column != 'id') {
              
                echo "<label class='form-label'>$column :</label>";
                echo "<input type='text' class='form-control' classname='form-control' name='$column' required><br>";
               
            }
        }
        echo "<button type='submit'>Ajouter</button>";
        echo "</form>";

        // Affichage des enregistrements
        echo "<h2>Liste des enregistrements dans $table_name</h2>";
        echo "<table border='1'>";
        echo "<tr>";
        foreach ($columns as $column) {
            echo "<th>$column</th>";
        }
        echo "<th>Actions</th>";
        echo "</tr>";

        // Préparer et exécuter la requête pour sélectionner toutes les lignes
        $stmt = $conn->query("SELECT * FROM $table_name");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($columns as $column) {
                echo "<td>" . htmlspecialchars($row[$column]) . "</td>";
            }
            echo "<td>
                <a href='crud.php?table=$table_name&id=".$row['id']."&action=edit'>Modifier</a>
                <a href='crud_action.php?table=$table_name&id=".$row['id']."&action=delete'>Supprimer</a>
            </td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}
?>
