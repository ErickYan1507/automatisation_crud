<?php
// Inclure le fichier de connexion PDO et les autres configurations
include 'db.php';

// Lire le message de la requête GET, s'il existe
$message = isset($_GET['message']) ? $_GET['message'] : '';

// Inclure le fichier pour la liste des tables

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une table</title>
    <link rel="stylesheet" href="styles.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .column{
            color:white;
        }
     input{
           
          
            border:blue 1px solid;
            outline:none;
        }

        label{
            color:rgb(56, 53, 53);
        }
    </style>
</head>
<body>

<h2>Création d'une Nouvelle Table</h2>
<form action="create_table_action.php" method="POST">
    <label>Nom de la table :</label>
    <input type="text" name="table_name" class="table" required><br>

    <h3>Ajouter des colonnes</h3>
    <div id="columns">
        <div class="column">
            <label>Nom de la colonne :</label>
            <input type="text" name="column_name[]" required>
            <label>Type de données :</label>
            <select name="data_type[]">
                <option value="INT">INT</option>
                <option value="VARCHAR(255)">VARCHAR(255)</option>
                <option value="TEXT">TEXT</option>
                <option value="DATE">DATE</option>
            </select>
        </div>
    </div>

    <button type="button" class="ajout" onclick="addColumn()">Ajouter une colonne</button><br><br>

    <button type="submit">Créer la table</button>
</form>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($message): ?>
        Swal.fire({
            title: 'Information',
            text: '<?php echo $message; ?>',
            icon: '<?php echo strpos($message, 'Erreur') === false ? 'success' : 'error'; ?>',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
});

function addColumn() {
    const columnDiv = document.createElement('div');
    columnDiv.className = 'column';
    columnDiv.innerHTML = `
        <label>Nom de la colonne :</label>
        <input type="text" name="column_name[]" required>
        <label>Type de données :</label>
        <select name="data_type[]">
            <option value="INT">INT</option>
            <option value="VARCHAR(255)">VARCHAR(255)</option>
            <option value="TEXT">TEXT</option>
            <option value="DATE">DATE</option>
        </select>
    `;
    document.getElementById('columns').appendChild(columnDiv);
}
</script>
<?php 
include 'tables_list.php';
?>
</body>
</html>
