<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Retorna o caminho público da raiz do projeto.
 * Funciona em /vetcare_manutencao ou em pastas aninhadas do XAMPP.
 */
function appBasePath(): string
{
    $projectRoot = realpath(dirname(__DIR__));
    $documentRoot = isset($_SERVER['DOCUMENT_ROOT'])
        ? realpath($_SERVER['DOCUMENT_ROOT'])
        : false;

    if ($projectRoot !== false && $documentRoot !== false) {
        $normalizedProject = str_replace('\\', '/', $projectRoot);
        $normalizedDocument = rtrim(str_replace('\\', '/', $documentRoot), '/');

        if ($normalizedProject === $normalizedDocument) {
            return '';
        }

        if (str_starts_with($normalizedProject, $normalizedDocument . '/')) {
            return '/' . ltrim(substr($normalizedProject, strlen($normalizedDocument)), '/');
        }
    }

    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $scriptDirectory = $scriptName === '' ? '' : rtrim(dirname($scriptName), '/\\');

    if (
        str_ends_with($scriptDirectory, '/tutores')
        || str_ends_with($scriptDirectory, '/animais')
        || str_ends_with($scriptDirectory, '/consultas')
        || str_ends_with($scriptDirectory, '/chamados')
        || str_ends_with($scriptDirectory, '/config')
        || str_ends_with($scriptDirectory, '/includes')
    ) {
        $scriptDirectory = dirname($scriptDirectory);
    }

    return $scriptDirectory === '.' ? '' : $scriptDirectory;
}

function appUrl(string $path = ''): string
{
    $base = rtrim(appBasePath(), '/');
    $path = ltrim($path, '/');

    if ($path === '') {
        return $base === '' ? '/' : $base;
    }

    return ($base === '' ? '' : $base) . '/' . $path;
}

function exigirLogin(): void
{
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . appUrl('login.php'));
        exit;
    }
}

function vetcareIcon(string $nome, string $classe = ''): string
{
    $classe = $classe !== '' ? ' class="icon ' . htmlspecialchars($classe) . '"' : ' class="icon"';

    $icone = [
        'pencil' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
        'trash-2' => '<path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v5"/><path d="M14 11v5"/>',
        'plus' => '<path d="M12 5v14"/><path d="M5 12h14"/>',
        'check' => '<path d="M20 6 9 17l-5-5"/>',
        'x' => '<path d="M18 6 6 18"/><path d="M6 6l12 12"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',
        'logout' => '<path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>',
    ];

    $conteudo = $icone[$nome] ?? $icone['x'];

    return '<svg' . $classe . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $conteudo . '</svg>';
}
