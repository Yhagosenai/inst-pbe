<?php
include "conexao.php";

if (isset($_POST['cadastrar'])) {
    $sql = $pdo->prepare("INSERT INTO publicacoes (id_usuario, legenda, imagem_url) VALUES (?, ?, ?)");
    $sql->execute([
        $_POST['id_usuario'],
        $_POST['legenda'],
        $_POST['imagem_url']
    ]);
}

if (isset($_POST['editar'])) {
    $sql = $pdo->prepare("UPDATE publicacoes SET id_usuario=?, legenda=?, imagem_url=? WHERE id_publicacao=?");
    $sql->execute([
        $_POST['id_usuario'],
        $_POST['legenda'],
        $_POST['imagem_url'],
        $_POST['id']
    ]);
}

if (isset($_GET['excluir'])) {
    $sql = $pdo->prepare("DELETE FROM curtidas WHERE id_publicacao=?");
    $sql->execute([$_GET['excluir']]);

    $sql = $pdo->prepare("DELETE FROM publicacoes WHERE id_publicacao=?");
    $sql->execute([$_GET['excluir']]);
}

$publicacoes = $pdo->query("
    SELECT publicacoes.*, usuarios.username
    FROM publicacoes
    INNER JOIN usuarios ON publicacoes.id_usuario = usuarios.id_usuario
")->fetchAll(PDO::FETCH_ASSOC);

$usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>CRUD Publicações</h1>

<form method="POST">
    <select name="id_usuario" required>
        <?php foreach ($usuarios as $u): ?>
            <option value="<?= $u['id_usuario'] ?>">
                <?= htmlspecialchars($u['username']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input name="legenda" placeholder="Legenda">
    <input name="imagem_url" placeholder="Imagem URL">
    <button name="cadastrar">Publicar</button>
</form>

<h2>Publicações cadastradas</h2>

<?php foreach ($publicacoes as $p): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $p['id_publicacao'] ?>">

        <select name="id_usuario">
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id_usuario'] ?>" <?= $u['id_usuario'] == $p['id_usuario'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['username']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input name="legenda" value="<?= htmlspecialchars($p['legenda'] ?? '') ?>">
        <input name="imagem_url" value="<?= htmlspecialchars($p['imagem_url'] ?? '') ?>">

        <button name="editar">Editar</button>
        <a href="?excluir=<?= $p['id_publicacao'] ?>">Excluir</a>
    </form>
<?php endforeach; ?>