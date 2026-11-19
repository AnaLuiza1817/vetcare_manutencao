<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
exigirLogin();

$id = (int) ($_GET['id'] ?? 0);
$r = [
    'animal_id' => '',
    'data_consulta' => '',
    'veterinario' => '',
    'motivo' => '',
    'status' => 'Agendada',
    'observacoes' => '',
];

if ($id > 0) {
    $statement = $pdo->prepare('SELECT * FROM consultas WHERE id = ?');
    $statement->execute([$id]);
    $r = $statement->fetch() ?: $r;
}

$animais = $pdo->query('SELECT id, nome FROM animais ORDER BY nome')->fetchAll();
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = trim($_POST['data_consulta'] ?? '');
    $veterinario = trim($_POST['veterinario'] ?? '');

    $dataConsulta = str_replace('T', ' ', $data);
    if (strlen($dataConsulta) === 16) {
        $dataConsulta .= ':00';
    }

    if ($veterinario === '') {
        $erro = 'Informe o veterinário.';
    } else {
        $dados = [
            (int) ($_POST['animal_id'] ?? 0),
            $dataConsulta,
            $veterinario,
            trim($_POST['motivo'] ?? ''),
            $_POST['status'] ?? 'Agendada',
            trim($_POST['observacoes'] ?? ''),
        ];

        if ($id > 0) {
            $statement = $pdo->prepare(
                'UPDATE consultas SET animal_id = ?, data_consulta = ?, veterinario = ?, motivo = ?, status = ?, observacoes = ? WHERE id = ?'
            );
            $statement->execute([...$dados, $id]);
        } else {
            $statement = $pdo->prepare(
                'INSERT INTO consultas(animal_id, data_consulta, veterinario, motivo, status, observacoes) VALUES(?, ?, ?, ?, ?, ?)'
            );
            $statement->execute($dados);
        }

        header('Location: index.php');
        exit;
    }
}

$titulo = $id > 0 ? 'Editar consulta' : 'Nova consulta';
include __DIR__ . '/../includes/header.php';
?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<section class="panel">
    <?php if ($erro): ?>
        <div class="alert"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="field">
            <label for="animal_id">Animal</label>
            <select id="animal_id" name="animal_id" required>
                <option value="">Selecione</option>
                <?php foreach ($animais as $animal): ?>
                    <option value="<?= (int) $animal['id'] ?>" <?= $r['animal_id'] == $animal['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($animal['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="data_consulta">Data e horário</label>
            <input
                id="data_consulta"
                type="datetime-local"
                name="data_consulta"
                value="<?= $r['data_consulta'] ? date('Y-m-d\TH:i', strtotime($r['data_consulta'])) : '' ?>"
                required
            >
        </div>

        <div class="field">
            <label for="veterinario">Veterinário *</label>
            <input id="veterinario" name="veterinario" value="<?= htmlspecialchars($r['veterinario']) ?>" required>
        </div>

        <div class="field">
            <label for="motivo">Motivo</label>
            <input id="motivo" name="motivo" value="<?= htmlspecialchars($r['motivo']) ?>">
        </div>

        <div class="field">
            <label for="status">Status</label>
            <select id="status" name="status">
                <?php foreach (['Agendada', 'Realizada', 'Cancelada'] as $opcao): ?>
                    <option value="<?= htmlspecialchars($opcao) ?>" <?= $r['status'] === $opcao ? 'selected' : '' ?>>
                        <?= htmlspecialchars($opcao) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="observacoes">Observações</label>
            <textarea id="observacoes" name="observacoes"><?= htmlspecialchars($r['observacoes']) ?></textarea>
        </div>

        <button type="submit" title="Salvar consulta" aria-label="Salvar consulta">
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
