<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
exigirLogin();

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    try {
        $statement = $pdo->prepare('DELETE FROM tutores WHERE id = ?');
        $statement->execute([$id]);
    } catch (PDOException $exception) {
        // A exclusão pode falhar por registros relacionados.
    }
}

header('Location: index.php');
exit;
