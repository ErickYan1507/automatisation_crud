<?php
// Afficher toutes les erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Débogage : afficher les données reçues
    if (!$input) {
        echo json_encode(['error' => 'Invalid JSON data']);
        http_response_code(400);
        exit();
    }
    if (!isset($input['tableName']) || !isset($input['fields']) || !is_array($input['fields'])) {
        echo json_encode(['error' => 'Invalid input data']);
        http_response_code(400);
        exit();
    }

    $tableName = $input['tableName'];
    $fields = $input['fields'];

    generateCrudCode($tableName, $fields);
    generateRouterCode($tableName);
    generateHtml($tableName, $fields);

    echo json_encode(['message' => 'CRUD generated successfully']);
}

function generateCrudCode($tableName, $fields) {
    $className = ucfirst($tableName);

    // Générer la liste des champs pour les requêtes SQL
    $fieldsList = implode(', ', array_map(function($field) {
        return $field['name'] . ' ' . $field['type'];
    }, $fields));
    
    $fieldNames = implode(', ', array_map(function($field) {
        return $field['name'];
    }, $fields));
    
    $fieldValues = implode(', ', array_map(function($field) {
        return "'\${$field['name']}'";
    }, $fields));

    $updateFields = implode(', ', array_map(function($field) {
        return $field['name'] . " = '\${$field['name']}'";
    }, $fields));

    $dslCode = <<<EOD
<?php

class {$className}DSL {
    private \$conn;

    public function __construct(\$conn) {
        \$this->conn = \$conn;
    }

    public function create(\$items) {
        \$query = "INSERT INTO {$tableName} ({$fieldNames}) VALUES ";
        \$values = [];
        foreach (\$items as \$item) {
            \$values[] = "('" . implode("', '", array_map(function(\$field) use (\$item) { return \$item[\$field['name']]; }, \$fields)) . "')";
        }
        \$query .= implode(', ', \$values);
        return \$this->conn->query(\$query);
    }

    public function read() {
        \$query = "SELECT * FROM {$tableName}";
        return \$this->conn->query(\$query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(\$id, \$newItem) {
        \$query = "UPDATE {$tableName} SET {$updateFields} WHERE id=\$id";
        return \$this->conn->query(\$query);
    }

    public function delete(\$id) {
        \$query = "DELETE FROM {$tableName} WHERE id=\$id";
        return \$this->conn->query(\$query);
    }
}
EOD;

    file_put_contents("{$className}DSL.php", $dslCode);
}

function generateRouterCode($tableName) {
    $className = ucfirst($tableName);
    $routerCode = <<<EOD
<?php

require_once 'db.php';
require_once '{$className}DSL.php';

\$dsn = "mysql:host=localhost;dbname=your_database";
\$username = "root";
\$password = "password";
\$options = [];

try {
    \$conn = new PDO(\$dsn, \$username, \$password, \$options);
} catch (PDOException \$e) {
    die("Erreur de connexion à la base de données: " . \$e->getMessage());
}

\$crud = new {$className}DSL(\$conn);

header('Content-Type: application/json');
\$method = \$_SERVER['REQUEST_METHOD'];
\$input = json_decode(file_get_contents('php://input'), true);

switch (\$method) {
    case 'POST':
        \$items = \$input['items'];
        \$result = \$crud->create(\$items);
        echo json_encode(['success' => \$result]);
        break;
    case 'GET':
        \$result = \$crud->read();
        echo json_encode(\$result);
        break;
    case 'PUT':
        \$id = \$_GET['id'];
        \$item = \$input['item'];
        \$result = \$crud->update(\$id, \$item);
        echo json_encode(['success' => \$result]);
        break;
    case 'DELETE':
        \$id = \$_GET['id'];
        \$result = \$crud->delete(\$id);
        echo json_encode(['success' => \$result]);
        break;
    default:
        echo json_encode(['error' => 'Invalid request method']);
        break;
}
EOD;

    file_put_contents("{$tableName}_router.php", $routerCode);
}

function generateHtml($tableName, $fields) {
    $fieldsNames = array_map(function($field) { return $field['name']; }, $fields);

    $htmlCode = <<<EOD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Interface for {$tableName}</title>
</head>
<body>
    <h1>{$tableName} Management</h1>
    <div>
        <input type="text" id="{$fieldsNames[0]}" placeholder="Enter {$fieldsNames[0]}">
        <button onclick="addItem()">Add Item</button>
    </div>
    <ul id="itemsList"></ul>

    <script>
        const apiUrl = '/{$tableName}_router.php';

        async function fetchItems() {
            const response = await fetch(apiUrl);
            const items = await response.json();
            const itemsList = document.getElementById('itemsList');
            itemsList.innerHTML = '';
            items.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item.{$fieldsNames[0]};
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Delete';
                deleteButton.onclick = () => deleteItem(item.id);
                li.appendChild(deleteButton);
                itemsList.appendChild(li);
            });
        }

        async function addItem() {
            const item = { {$fieldsNames[0]}: document.getElementById('{$fieldsNames[0]}').value };
            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ items: [item] })
            });
            const result = await response.json();
            if (result.success) {
                fetchItems();
                document.getElementById('{$fieldsNames[0]}').value = '';
            }
        }

        async function deleteItem(id) {
            const response = await fetch(apiUrl + '?id=' + id, {
                method: 'DELETE'
            });
            const result = await response.json();
            if (result.success) {
                fetchItems();
            }
        }

        fetchItems();
    </script>
</body>
</html>
EOD;

    file_put_contents("{$tableName}_interface.html", $htmlCode);
}
?>
