<?php
require_once __DIR__.'/../config/database.php'; require_once __DIR__.'/../config/auth.php'; exigirLogin();
$titulo='Tutores';
$busca=trim($_GET['busca'] ?? '');
if($busca !== ''){
    $sql="SELECT * FROM tutores WHERE nome LIKE ?";
    $param=['%'.$busca.'%'];
} else {
    $sql="SELECT * FROM tutores";
    $param=[];
}
$stmt=$pdo->prepare($sql." ORDER BY nome"); $stmt->execute($param); $rows=$stmt->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<h1>Tutores</h1>
<p class="muted">Cadastro e consulta de responsáveis pelos animais.</p>

<div class="actions">
    <a class="btn" href="form.php" title="Adicionar novo tutor" aria-label="Adicionar novo tutor">
        <?= vetcareIcon('plus') ?>
        <span>Novo tutor</span>
    </a>
</div>

<section class="panel search-panel">
    <form method="get" class="search-form">
        <div class="field inline-field">
            <label for="busca">Buscar tutor</label>
            <input id="busca" name="busca" placeholder="Digite o nome" value="<?= htmlspecialchars($busca) ?>">
        </div>
        <button type="submit" title="Consultar tutores" aria-label="Consultar tutores">
            <?= vetcareIcon('search') ?>
            <span>Buscar</span>
        </button>
    </form>
</section>

<div class="panel table-wrap">
    <table>
        <tr>
            <th>Nome</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>
        <?php foreach($rows as $r): ?>
            <tr>
                <td><?=htmlspecialchars($r['nome'])?></td>
                <td><?=htmlspecialchars($r['telefone'])?></td>
                <td><?=htmlspecialchars($r['email'])?></td>
                <td><span class="status-badge <?= $r['ativo'] ? 'active' : 'inactive' ?>"><?= $r['ativo'] ? 'Ativo' : 'Inativo' ?></span></td>
                <td class="actions-cell">
                    <a class="link-btn" href="form.php?id=<?=$r['id']?>" title="Editar tutor" aria-label="Editar tutor <?= htmlspecialchars($r['nome']) ?>">
                        <?= vetcareIcon('pencil') ?>
                        <span>Editar</span>
                    </a>
                    <a class="link-btn danger" href="excluir.php?id=<?=$r['id']?>" title="Excluir tutor" aria-label="Excluir tutor <?= htmlspecialchars($r['nome']) ?>">
                        <?= vetcareIcon('trash-2') ?>
                        <span>Excluir</span>
                    </a>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>