<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
exigirLogin();

$titulo = 'Consultas';
$status = $_GET['status'] ?? '';

$sql = 'SELECT c.*, a.nome AS animal
        FROM consultas c
        JOIN animais a ON a.id = c.animal_id';
$params = [];

if ($status !== '') {
    $sql .= ' WHERE c.status = ?';
    $params[] = $status;
}

$sql .= ' ORDER BY c.data_consulta DESC';

$statement = $pdo->prepare($sql);
$statement->execute($params);
$rows = $statement->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<h1>Consultas</h1>
<p class="muted">Agenda e histórico de atendimentos.</p>

<div class="actions">
    <a class="btn" href="form.php" title="Adicionar nova consulta" aria-label="Adicionar nova consulta">
        <?= vetcareIcon('plus') ?>
        <span>Nova consulta</span>
    </a>
</div>

<section class="panel">
    <form method="get">
        <div class="field">
            <label for="status">Filtrar por status</label>
            <select id="status" name="status">
                <option value="">Todos</option>
                <?php foreach (['Agendada', 'Realizada', 'Cancelada'] as $opcao): ?>
                    <option value="<?= htmlspecialchars($opcao) ?>" <?= $status === $opcao ? 'selected' : '' ?>>
                        <?= htmlspecialchars($opcao) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" title="Consultar por status" aria-label="Consultar por status">
            <?= vetcareIcon('search') ?>
            <span>Filtrar</span>
        </button>
    </form>
</section>

<div class="panel table-wrap">
    <table>
        <tr>
            <th>Animal</th>
            <th>Data</th>
            <th>Veterinário</th>
            <th>Motivo</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>

        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['animal']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($row['data_consulta'])) ?></td>
                <td><?= htmlspecialchars($row['veterinario']) ?></td>
                <td><?= htmlspecialchars($row['motivo']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td class="actions-cell">
                    <a class="link-btn" href="form.php?id=<?= (int) $row['id'] ?>" title="Editar consulta" aria-label="Editar consulta">
                        <?= vetcareIcon('pencil') ?>
                        <span>Editar</span>
                    </a>
                    <a class="link-btn danger" href="excluir.php?id=<?= (int) $row['id'] ?>" title="Excluir consulta" aria-label="Excluir consulta">
                        <?= vetcareIcon('trash-2') ?>
                        <span>Excluir</span>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
