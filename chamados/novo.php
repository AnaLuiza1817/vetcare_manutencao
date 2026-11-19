<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
exigirLogin();

$titulo = 'Abrir chamado';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [];

    foreach ([
        'titulo',
        'modulo',
        'solicitante',
        'equipe',
        'descricao',
        'passos',
        'resultado_esperado',
        'resultado_obtido',
        'severidade',
        'prioridade',
    ] as $campo) {
        $dados[$campo] = trim($_POST[$campo] ?? '');
    }

    $obrigatorios = ['titulo', 'solicitante', 'descricao', 'passos', 'resultado_esperado', 'resultado_obtido'];

    foreach ($obrigatorios as $campo) {
        if ($dados[$campo] === '') {
            $erro = 'Preencha todos os campos obrigatórios.';
            break;
        }
    }

    if ($erro === '') {
        $statement = $pdo->prepare(
            'INSERT INTO chamados(
                titulo, modulo, solicitante, equipe, descricao,
                passos, resultado_esperado, resultado_obtido,
                severidade, prioridade
            ) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $statement->execute([
            $dados['titulo'],
            $dados['modulo'],
            $dados['solicitante'],
            $dados['equipe'],
            $dados['descricao'],
            $dados['passos'],
            $dados['resultado_esperado'],
            $dados['resultado_obtido'],
            $dados['severidade'],
            $dados['prioridade'],
        ]);

        $id = (int) $pdo->lastInsertId();

        $historico = $pdo->prepare(
            'INSERT INTO historico_chamados(chamado_id, autor, comentario, status_novo)
             VALUES(?, ?, ?, ?)'
        );
        $historico->execute([
            $id,
            $dados['solicitante'],
            'Chamado registrado na central de suporte.',
            'Aberto',
        ]);

        header('Location: ver.php?id=' . $id . '&novo=1');
        exit;
    }
}

include __DIR__ . '/../includes/header.php';
?>

<h1>Abrir chamado técnico</h1>
<p class="muted">Descreva o problema de forma que outra pessoa consiga reproduzi-lo.</p>

<?php if ($erro): ?>
    <div class="alert"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="post">
    <section class="panel">
        <h2>1. Identificação</h2>

        <div class="field">
            <label for="titulo">Título objetivo *</label>
            <input id="titulo" name="titulo" maxlength="160" placeholder="Ex.: Filtro de consultas não aplica o status selecionado" value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>" required>
        </div>

        <div class="grid">
            <div class="field">
                <label for="modulo">Módulo *</label>
                <select id="modulo" name="modulo" required>
                    <?php foreach (['Login', 'Dashboard', 'Tutores', 'Animais', 'Consultas', 'Outro'] as $valor): ?>
                        <option value="<?= htmlspecialchars($valor) ?>" <?= ($_POST['modulo'] ?? '') === $valor ? 'selected' : '' ?>>
                            <?= htmlspecialchars($valor) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label for="solicitante">Nome do solicitante/equipe *</label>
                <input id="solicitante" name="solicitante" value="<?= htmlspecialchars($_POST['solicitante'] ?? '') ?>" placeholder="Ex.: Equipe 02 — Ana" required>
            </div>

            <div class="field">
                <label for="equipe">Turma/equipe</label>
                <input id="equipe" name="equipe" value="<?= htmlspecialchars($_POST['equipe'] ?? '') ?>" placeholder="Ex.: TDS 2ª série — Grupo B">
            </div>
        </div>
    </section>

    <section class="panel">
        <h2>2. Descrição e reprodução</h2>

        <div class="field">
            <label for="descricao">Descrição do problema *</label>
            <textarea id="descricao" name="descricao" placeholder="O que aconteceu? Em qual situação?" required><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
        </div>

        <div class="field">
            <label for="passos">Passos para reproduzir *</label>
            <textarea id="passos" name="passos" placeholder="1. Acesse...&#10;2. Clique em...&#10;3. Observe..." required><?= htmlspecialchars($_POST['passos'] ?? '') ?></textarea>
        </div>

        <div class="field">
            <label for="resultado_esperado">Resultado esperado *</label>
            <textarea id="resultado_esperado" name="resultado_esperado" required><?= htmlspecialchars($_POST['resultado_esperado'] ?? '') ?></textarea>
        </div>

        <div class="field">
            <label for="resultado_obtido">Resultado obtido *</label>
            <textarea id="resultado_obtido" name="resultado_obtido" required><?= htmlspecialchars($_POST['resultado_obtido'] ?? '') ?></textarea>
        </div>
    </section>

    <section class="panel">
        <h2>3. Classificação</h2>

        <div class="grid">
            <div class="field">
                <label for="severidade">Severidade</label>
                <select id="severidade" name="severidade">
                    <?php foreach (['Baixa', 'Média', 'Alta', 'Crítica'] as $valor): ?>
                        <option value="<?= htmlspecialchars($valor) ?>" <?= ($_POST['severidade'] ?? 'Média') === $valor ? 'selected' : '' ?>><?= htmlspecialchars($valor) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label for="prioridade">Prioridade</label>
                <select id="prioridade" name="prioridade">
                    <?php foreach (['Baixa', 'Média', 'Alta', 'Urgente'] as $valor): ?>
                        <option value="<?= htmlspecialchars($valor) ?>" <?= ($_POST['prioridade'] ?? 'Média') === $valor ? 'selected' : '' ?>><?= htmlspecialchars($valor) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <p class="muted">Severidade representa o impacto técnico. Prioridade indica a urgência de atendimento.</p>
    </section>

    <div class="actions">
        <button type="submit" title="Registrar chamado" aria-label="Registrar chamado">
            <?= vetcareIcon('check') ?>
            <span>Registrar chamado</span>
        </button>
        <a class="btn btn-neutral" href="index.php" title="Cancelar abertura do chamado" aria-label="Cancelar abertura do chamado">
            <?= vetcareIcon('x') ?>
            <span>Cancelar</span>
        </a>
    </div>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
