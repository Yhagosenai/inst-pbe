<?php
include "conexao.php";

if (isset($_POST['cadastrar'])) {
    $sql = $pdo->prepare("INSERT INTO usuarios (nome, username, email, senha) VALUES (?, ?, ?, ?)");
    $sql->execute([
        $_POST['nome'],
        $_POST['username'],
        $_POST['email'],
        password_hash($_POST['senha'], PASSWORD_DEFAULT)
    ]);
}

if (isset($_POST['editar'])) {
    $sql = $pdo->prepare("UPDATE usuarios SET nome=?, username=?, email=? WHERE id_usuario=?");
    $sql->execute([
        $_POST['nome'],
        $_POST['username'],
        $_POST['email'],
        $_POST['id']
    ]);
}

if (isset($_GET['excluir'])) {
    $sql = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario=?");
    $sql->execute([$_GET['excluir']]);
}

$usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>CRUD Usuários</h1>

<form method="POST">
    <input name="nome" placeholder="Nome" required>
    <input name="username" placeholder="Username" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="senha" type="password" placeholder="Senha" required>
    <button name="cadastrar">Cadastrar</button>
</form>

<h2>Usuários cadastrados</h2>

<?php foreach ($usuarios as $u): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $u['id_usuario'] ?>">
        <input name="nome" value="<?= htmlspecialchars($u['nome']) ?>">
        <input name="username" value="<?= htmlspecialchars($u['username']) ?>">
        <input name="email" value="<?= htmlspecialchars($u['email']) ?>">
        <button name="editar">Editar</button>
        <a href="?excluir=<?= $u['id_usuario'] ?>">Excluir</a>
    </form>
<?php endforeach; ?>