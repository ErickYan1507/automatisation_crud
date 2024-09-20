<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Interface</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }

        .scene {
            position: relative;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, #87CEEB, #4682B4);
            overflow: hidden;
        }

        .ground {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 30%;
            background-color: #228B22;
        }

        .cloud {
            position: absolute;
            background: white;
            border-radius: 50%;
            opacity: 0.8;
            animation: float 20s infinite linear;
        }

        .cloud::before, .cloud::after {
            content: '';
            position: absolute;
            background: white;
            border-radius: 50%;
        }

        .cloud1 {
            width: 100px;
            height: 40px;
            top: 10%;
            left: -100px;
        }

        .cloud1::before {
            width: 50px;
            height: 50px;
            top: -25px;
            left: 10px;
        }

        .cloud1::after {
            width: 70px;
            height: 70px;
            top: -35px;
            right: 10px;
        }

        .cloud2 {
            width: 120px;
            height: 48px;
            top: 20%;
            left: -120px;
            animation-delay: -10s;
        }

        .cloud2::before {
            width: 60px;
            height: 60px;
            top: -30px;
            left: 12px;
        }

        .cloud2::after {
            width: 80px;
            height: 80px;
            top: -40px;
            right: 12px;
        }

        @keyframes float {
            0% { transform: translateX(-100px); }
            100% { transform: translateX(calc(100vw + 100px)); }
        }

        .tree {
            position: absolute;
            bottom: 30%;
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-bottom: 100px solid #006400;
        }

        .tree::after {
            content: '';
            position: absolute;
            width: 10px;
            height: 20px;
            background: #8B4513;
            bottom: -110px;
            left: -5px;
        }

        .tree1 { left: 10%; }
        .tree2 { left: 30%; }
        .tree3 { left: 70%; }
        .tree4 { left: 90%; }

        .card {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2%;
            z-index: 10;
        }

        .card:hover {
            background-color: rgba(200, 200, 200, 0.8);
        }
    </style>
</head>
<body>
    <div class="scene">
        <div class="ground"></div>
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
        <div class="tree tree1"></div>
        <div class="tree tree2"></div>
        <div class="tree tree3"></div>
        <div class="tree tree4"></div>

        <div class="card">
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
                    echo "<div class='container p-4'>";
                    echo "<h2 class='pb-3'>Ajouter un enregistrement dans la table $table_name</h2>";
                    echo "<form action='crud_action.php?table=$table_name&action=insert' method='POST'>";
                    foreach ($columns as $column) {
                        if ($column != 'id') {
                            echo "<div class='mb-3'>";
                            echo "<label class='form-label'>$column :</label>";
                            echo "<input type='text' class='form-control' name='$column' required>";
                            echo "</div>";
                        }
                    }
                    echo "<button type='submit' class='btn btn-primary'>Ajouter</button>";
                    echo "</form>";
                    echo "</div>";

                    // Affichage des enregistrements avec padding
                    echo "<div class='container p-4'>";
                    echo "<h2 class='pb-3'>Liste des enregistrements dans $table_name</h2>";
                    echo "<table class='table table-bordered table-striped'>";
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
                    echo "</div>";

                } catch (PDOException $e) {
                    echo "<div class='alert alert-danger p-4'>Erreur: " . $e->getMessage() . "</div>";
                }
            }
            ?>
        </div>
    </div>

    <script src="./bootstrap/js/bootstrap.js"></script>
    <script>
        function createCloud() {
            const cloud = document.createElement('div');
            cloud.classList.add('cloud');
            cloud.style.top = Math.random() * 30 + '%';
            cloud.style.left = '-100px';
            cloud.style.animationDuration = (Math.random() * 10 + 15) + 's';
            
            const size = Math.random() * 50 + 50;
            cloud.style.width = size + 'px';
            cloud.style.height = size * 0.4 + 'px';
            
            document.querySelector('.scene').appendChild(cloud);
            
            setTimeout(() => {
                cloud.remove();
            }, 25000);
        }
        
        setInterval(createCloud, 3000);
        
        function createTree() {
            const tree = document.createElement('div');
            tree.classList.add('tree');
            tree.style.left = Math.random() * 100 + '%';
            tree.style.bottom = '30%';
            
            const size = Math.random() * 50 + 50;
            tree.style.borderLeft = size * 0.2 + 'px solid transparent';
            tree.style.borderRight = size * 0.2 + 'px solid transparent';
            tree.style.borderBottom = size + 'px solid #006400';
            
            document.querySelector('.scene').appendChild(tree);
        }
        
        for (let i = 0; i < 10; i++) {
            createTree();
        }
    </script>
</body>
</html>
