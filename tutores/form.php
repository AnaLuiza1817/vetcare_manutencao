<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/auth.php';
exigirLogin();

$id = (int) ($_GET['id'] ?? 0);
$r = [
    'nome' => '',
    'telefone' => '',
    'email' => '',
    'endereco' => '',
    'ativo' => 1,
];

if ($id > 0) {
    $statement = $pdo->prepare('SELECT * FROM tutores WHERE id = ?');
    $statement->execute([$id]);
    $r = $statement->fetch() ?: $r;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');

    if ($nome === '') {
        $erro = 'Informe o nome.';
    } else {
        if ($id > 0) {
            $statement = $pdo->prepare(
                'UPDATE tutores SET nome = ?, telefone = ?, email = ?, endereco = ?, ativo = ? WHERE id = ?'
            );
            $statement->execute([
                $nome,
                $telefone,
                $email,
                $endereco,
                (int) ($_POST['ativo'] ?? 1),
                $id,
            ]);
        } else {
            $statement = $pdo->prepare(
                'INSERT INTO tutores(nome, telefone, email, endereco) VALUES(?, ?, ?, ?)'
            );
            $statement->execute([$nome, $telefone, $email, $endereco]);
        }

        header('Location: index.php');
        exit;
    }
}

$titulo = $id > 0 ? 'Editar tutor' : 'Novo tutor';
include __DIR__ . '/../includes/header.php';
?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<section class="panel">
    <?php if ($erro): ?>
        <div class="alert"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="field">
            <label for="nome">Nome *</label>
            <input id="nome" name="nome" value="<?= htmlspecialchars($r['nome']) ?>" required>
        </div>

        <div class="field">
            <label for="telefone">Telefone</label>
            <input id="telefone" name="telefone" value="<?= htmlspecialchars($r['telefone']) ?>">
        </div>

        <div class="field">
            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" value="<?= htmlspecialchars($r['email']) ?>">
        </div>

        <div class="field">
            <label for="endereco">Endereço</label>
            <input id="endereco" name="endereco" value="<?= htmlspecialchars($r['endereco']) ?>">
        </div>

        <?php if ($id > 0): ?>
            <div class="field">
                <label for="ativo">Situação</label>
                <select id="ativo" name="ativo">
                    <option value="1" <?= $r['ativo'] ? 'selected' : '' ?>>Ativo</option>
                    <option value="0" <?= !$r['ativo'] ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>
        <?php endif; ?>

        <button type="submit" title="Salvar tutor" aria-label="Salvar tutor">
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
