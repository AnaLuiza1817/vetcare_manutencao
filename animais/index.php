<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
exigirLogin();

$titulo = 'Animais';

$rows = $pdo
    ->query(
        "SELECT a.*, t.nome AS tutor
         FROM animais a
         JOIN tutores t ON t.id = a.tutor_id
         ORDER BY a.nome"
    )
    ->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<h1>Animais</h1>
<p class="muted">Pacientes cadastrados na clínica.</p>

<div class="actions">
    <a class="btn" href="form.php" title="Adicionar novo animal" aria-label="Adicionar novo animal">
        <?= vetcareIcon('plus') ?>
        <span>Novo animal</span>
    </a>
</div>

<div class="panel table-wrap">
    <table>
        <tr>
            <th>Nome</th>
            <th>Espécie</th>
            <th>Raça</th>
            <th>Tutor</th>
            <th>Nascimento</th>
            <th>Idade</th>
            <th>Ações</th>
        </tr>

        <?php foreach ($rows as $r): ?>
            <?php
                $idade = 0;
                if (!empty($r['nascimento'])) {
                    try {
                        $dataNascimento = new DateTimeImmutable($r['nascimento']);
                        $idade = (new DateTimeImmutable('today'))->diff($dataNascimento)->y;
                    } catch (Exception $e) {
                        $idade = 0;
                    }
                }
            ?>
            <tr>
                <td><?= htmlspecialchars($r['nome']) ?></td>
                <td><?= htmlspecialchars($r['especie']) ?></td>
                <td><?= htmlspecialchars($r['raca']) ?></td>
                <td><?= htmlspecialchars($r['tutor']) ?></td>
                <td><?= $r['nascimento'] ? date('d/m/Y', strtotime($r['nascimento'])) : '-' ?></td>
                <td><?= $idade ?> ano(s)</td>
                <td class="actions-cell">
                    <a class="link-btn" href="form.php?id=<?= (int) $r['id'] ?>" title="Editar animal" aria-label="Editar animal <?= htmlspecialchars($r['nome']) ?>">
                        <?= vetcareIcon('pencil') ?>
                        <span>Editar</span>
                    </a>
                    <a class="link-btn danger" href="excluir.php?id=<?= (int) $r['id'] ?>" title="Excluir animal" aria-label="Excluir animal <?= htmlspecialchars($r['nome']) ?>">
                        <?= vetcareIcon('trash-2') ?>
                        <span>Excluir</span>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
