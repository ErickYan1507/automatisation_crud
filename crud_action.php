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
            // Utilisation de SweetAlert pour afficher un message de succès
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: 'Nouvel enregistrement ajouté avec succès!',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'crud.php?table=$table_name'; // Rediriger après confirmation
                        }
                    });
                  </script>";
        } else {
            // Utilisation de SweetAlert pour afficher un message d'erreur
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Erreur lors de l\'ajout de l\'enregistrement!',
                        confirmButtonText: 'OK'
                    });
                  </script>";
        }
    } elseif ($action == 'delete') {
        $id = $_GET['id'];
        $query = "DELETE FROM $table_name WHERE id = :id";
        
        $stmt = $conn->prepare($query);
        if ($stmt->execute([':id' => $id])) {
            // Utilisation de SweetAlert pour afficher un message de succès lors de la suppression
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: 'Enregistrement supprimé avec succès!',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'crud.php?table=$table_name'; // Rediriger après confirmation
                        }
                    });
                  </script>";
        } else {
            // Utilisation de SweetAlert pour afficher un message d'erreur
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Erreur lors de la suppression de l\'enregistrement!',
                        confirmButtonText: 'OK'
                    });
                  </script>";
        }
    }
} catch (PDOException $e) {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Erreur: " . $e->getMessage() . "',
                confirmButtonText: 'OK'
            });
          </script>";
}
?>

<!-- Inclusion de SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
