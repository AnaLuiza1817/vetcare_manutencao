<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!function_exists('appUrl')) {
    function appUrl(string $path = ''): string
    {
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $base = $script === '' ? '' : rtrim(dirname($script), '/\\');
        $base = $base === '.' ? '' : $base;
        $path = ltrim($path, '/');

        if ($path === '') {
            return $base === '' ? '/' : $base;
        }

        return ($base === '' ? '' : $base) . '/' . $path;
    }
}

$titulo = $titulo ?? 'VetCare';
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($titulo) ?> | VetCare</title>
<link rel="stylesheet" href="<?= htmlspecialchars(appUrl('assets/style.css')) ?>">
</head>
<body>
<header class="topbar">
  <a class="brand" href="<?= htmlspecialchars(appUrl('index.php')) ?>">🐾 VetCare</a>
  <nav>
    <?php if (!empty($_SESSION['usuario_id'])): ?>
      <a href="<?= htmlspecialchars(appUrl('index.php')) ?>">Painel</a>
      <a href="<?= htmlspecialchars(appUrl('tutores/index.php')) ?>">Tutores</a>
      <a href="<?= htmlspecialchars(appUrl('animais/index.php')) ?>">Animais</a>
      <a href="<?= htmlspecialchars(appUrl('consultas/index.php')) ?>">Consultas</a>
      <a href="<?= htmlspecialchars(appUrl('chamados/index.php')) ?>">Suporte</a>
      <a href="<?= htmlspecialchars(appUrl('logout.php')) ?>">Sair</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">
