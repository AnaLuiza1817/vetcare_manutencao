<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function appBasePath(): string
{
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    if ($script === '') {
        return '';
    }

    $base = rtrim(dirname($script), '/\\');
    return $base === '.' ? '' : $base;
}

function appUrl(string $path = ''): string
{
    $base = appBasePath();
    $path = ltrim($path, '/');

    if ($path === '') {
        return $base === '' ? '/' : $base;
    }

    return ($base === '' ? '' : $base) . '/' . $path;
}

function exigirLogin() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . appUrl('login.php'));
        exit;
    }
}
