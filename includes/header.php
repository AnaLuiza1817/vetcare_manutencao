<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    <link rel="stylesheet" href="<?= htmlspecialchars(appUrl('assets/style.css') . '?v=20261119') ?>">
</head>
<body>
<header class="topbar">
    <a class="brand" href="<?= htmlspecialchars(appUrl('index.php')) ?>">🐾 VetCare</a>

    <nav aria-label="Navegação principal">
        <?php if (!empty($_SESSION['usuario_id'])): ?>
            <a href="<?= htmlspecialchars(appUrl('index.php')) ?>">Painel</a>
            <a href="<?= htmlspecialchars(appUrl('tutores/index.php')) ?>">Tutores</a>
            <a href="<?= htmlspecialchars(appUrl('animais/index.php')) ?>">Animais</a>
            <a href="<?= htmlspecialchars(appUrl('consultas/index.php')) ?>">Consultas</a>
            <a href="<?= htmlspecialchars(appUrl('chamados/index.php')) ?>">Suporte</a>
            <button
                type="button"
                class="nav-link logout-button"
                id="abrir-confirmacao-sair"
                aria-haspopup="dialog"
                aria-controls="modal-sair"
            >
                <?= vetcareIcon('logout', 'icon-nav') ?>
                <span>Sair</span>
            </button>
        <?php endif; ?>
    </nav>
</header>

<main class="container">

<?php if (!empty($_SESSION['usuario_id'])): ?>
<div class="modal-overlay" id="modal-sair" role="dialog" aria-modal="true" aria-labelledby="titulo-modal-sair" aria-describedby="mensagem-modal-sair" hidden>
    <div class="modal-confirmacao">
        <div class="modal-icon modal-icon-neutro" aria-hidden="true">
            <?= vetcareIcon('logout') ?>
        </div>

        <h2 id="titulo-modal-sair">Deseja sair?</h2>
        <p id="mensagem-modal-sair">Tem certeza de que deseja encerrar sua sessão no VetCare?</p>

        <div class="modal-acoes">
            <button type="button" class="btn btn-neutral" id="cancelar-sair">
                <?= vetcareIcon('x') ?>
                <span>Cancelar</span>
            </button>

            <a class="btn btn-danger" href="<?= htmlspecialchars(appUrl('logout.php')) ?>">
                <?= vetcareIcon('check') ?>
                <span>Sim, sair</span>
            </a>
        </div>
    </div>
</div>

<script>
(function () {
    const botaoAbrir = document.getElementById('abrir-confirmacao-sair');
    const modal = document.getElementById('modal-sair');
    const botaoCancelar = document.getElementById('cancelar-sair');

    if (!botaoAbrir || !modal || !botaoCancelar) {
        return;
    }

    function abrirModal() {
        modal.hidden = false;
        document.body.classList.add('modal-aberto');
        botaoCancelar.focus();
    }

    function fecharModal() {
        modal.hidden = true;
        document.body.classList.remove('modal-aberto');
        botaoAbrir.focus();
    }

    botaoAbrir.addEventListener('click', abrirModal);
    botaoCancelar.addEventListener('click', fecharModal);

    modal.addEventListener('click', function (evento) {
        if (evento.target === modal) {
            fecharModal();
        }
    });

    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape' && !modal.hidden) {
            fecharModal();
        }
    });
})();
</script>
<?php endif; ?>
