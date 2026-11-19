<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
exigirLogin();

$id = (int) ($_GET['id'] ?? 0);

$r = [
    'tutor_id'   => '',
    'nome'       => '',
    'especie'    => '',
    'raca'       => '',
    'nascimento' => '',
    'sexo'       => '',
    'observacoes'=> '',
    'ativo'      => 1,
];

if ($id) {
    $s = $pdo->prepare('SELECT * FROM animais WHERE id = ?');
    $s->execute([$id]);
    $r = $s->fetch() ?: $r;
}

$tutores = $pdo->query('SELECT id, nome FROM tutores ORDER BY nome')->fetchAll();
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $especie = trim($_POST['especie'] ?? '');

    if ($nome === '') {
        $erro = 'Informe o nome do animal.';
    } else {
        $dados = [
            (int) ($_POST['tutor_id'] ?? 0),
            $nome,
            $especie,
            $_POST['raca'] ?? '',
            !empty($_POST['nascimento']) ? $_POST['nascimento'] : null,
            $_POST['sexo'] ?? '',
            $_POST['observacoes'] ?? '',
        ];

        if ($id) {
            $s = $pdo->prepare('UPDATE animais SET tutor_id = ?, nome = ?, especie = ?, raca = ?, nascimento = ?, sexo = ?, observacoes = ?, ativo = ? WHERE id = ?');
            $s->execute([
                ...$dados,
                (int) ($_POST['ativo'] ?? 1),
                $id,
            ]);
        } else {
            $s = $pdo->prepare('INSERT INTO animais(tutor_id, nome, especie, raca, nascimento, sexo, observacoes) VALUES(?, ?, ?, ?, ?, ?, ?)');
            $s->execute($dados);
        }

        header('Location: index.php');
        exit;
    }
}

$titulo = $id ? 'Editar animal' : 'Novo animal';
include __DIR__ . '/../includes/header.php';
?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<section class="panel">
    <?php if ($erro): ?>
        <div class="alert"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="field">
            <label for="tutor_id">Tutor</label>
            <select id="tutor_id" name="tutor_id" required>
                <option value="">Selecione</option>
                <?php foreach ($tutores as $t): ?>
                    <option value="<?= (int) $t['id'] ?>" <?= $r['tutor_id'] == $t['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="nome">Nome *</label>
            <input id="nome" name="nome" value="<?= htmlspecialchars($r['nome']) ?>" required>
        </div>

        <div class="field">
            <label for="especie">Espécie</label>
            <input id="especie" name="especie" value="<?= htmlspecialchars($r['especie']) ?>">
        </div>

        <div class="field">
            <label for="raca">Raça</label>
            <input id="raca" name="raca" value="<?= htmlspecialchars($r['raca']) ?>">
        </div>

        <div class="field">
            <label for="nascimento">Data de nascimento</label>
            <input id="nascimento" type="date" name="nascimento" value="<?= htmlspecialchars($r['nascimento']) ?>">
        </div>

        <div class="field">
            <label for="sexo">Sexo</label>
            <select id="sexo" name="sexo">
                <option value="">Selecione</option>
                <option value="Macho" <?= $r['sexo'] === 'Macho' ? 'selected' : '' ?>>Macho</option>
                <option value="Fêmea" <?= $r['sexo'] === 'Fêmea' ? 'selected' : '' ?>>Fêmea</option>
            </select>
        </div>

        <div class="field">
            <label for="observacoes">Observações</label>
            <textarea id="observacoes" name="observacoes"><?= htmlspecialchars($r['observacoes']) ?></textarea>
        </div>

        <?php if ($id): ?>
            <div class="field">
                <label for="ativo">Situação</label>
                <select id="ativo" name="ativo">
                    <option value="1" <?= $r['ativo'] ? 'selected' : '' ?>>Ativo</option>
                    <option value="0" <?= !$r['ativo'] ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>
        <?php endif; ?>

        <button type="submit" title="Salvar animal" aria-label="Salvar animal">
            <?= vetcareIcon('check') ?>
            <span>Salvar</span>
        </button>
        <a class="btn btn-neutral" href="index.php" title="Cancelar edição" aria-label="Cancelar edição">
            <?= vetcareIcon('x') ?>
            <span>Cancelar</span>
        </a>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
