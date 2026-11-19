<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
exigirLogin();

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $statement = $pdo->prepare('DELETE FROM consultas WHERE id = ?');
    $statement->execute([$id]);
}

header('Location: index.php');
exit;
