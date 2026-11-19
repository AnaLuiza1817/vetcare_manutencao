<?php
require_once __DIR__.'/config/database.php';
require_once __DIR__.'/config/auth.php';
if (session_status()===PHP_SESSION_NONE) session_start();
$erro='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $usuario=trim($_POST['usuario'] ?? '');
    $senha=$_POST['senha'] ?? '';
    $stmt=$pdo->prepare("SELECT * FROM usuarios WHERE usuario=?");
    $stmt->execute([$usuario]);
    $conta=$stmt->fetch();
    $senhaValida = $conta !== false && $senha === $conta['senha'];

    if (!$conta || !$senhaValida) {
        $erro='Credenciais inválidas.';
    } else {
        session_regenerate_id(true);
        $_SESSION['usuario_id']=$conta['id'];
        $_SESSION['usuario_nome']=$conta['nome'];
        header('Location: ' . appUrl('index.php')); exit;
    }
}
$titulo='Entrar'; include __DIR__.'/includes/header.php';
?>
<div class="login-box"><h1>🐾 VetCare</h1><p class="muted">Acesse o sistema da clínica.</p>
<?php if($erro): ?><div class="alert"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<form method="post">
    <div class="field"><label for="usuario">Usuário</label><input id="usuario" name="usuario" required></div>
    <div class="field"><label for="senha">Senha</label><input id="senha" type="password" name="senha" required></div>
    <button type="submit" title="Entrar no VetCare" aria-label="Entrar no VetCare">
        <?= vetcareIcon('check') ?>
        <span>Entrar</span>
    </button>
</form>
<p class="muted">Acesso de demonstração: admin / admin123</p></div>
<?php include __DIR__.'/includes/footer.php'; ?>
