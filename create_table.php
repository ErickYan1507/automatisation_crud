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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>

<h1 class="">CREATION D'UNE TABLE CRUD</h1>

<?php if ($message): ?>
    <div class="alert <?php echo strpos($message, 'Erreur') === false ? 'alert-success' : 'alert-danger'; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

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

    <button type="button" onclick="addColumn()" class="btn btn-secondary">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
    </svg> Colonne
    </button><br><br>

    <button type="submit" class="btn btn-success">Créer la table</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

<script>
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
