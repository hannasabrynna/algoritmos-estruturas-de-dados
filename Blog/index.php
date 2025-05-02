<?php
require 'auth.php';
include 'comments.php';
$user = getCurrentUser();
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Comentários</title></head>
<body>
    <h1>Comentários</h1>

    <?php if ($user): ?>
        <p>Olá, <strong><?= htmlspecialchars($user['name']) ?></strong> | <a href="logout.php">Sair</a></p>
        <form method="post" action="add_comment.php">
            <input type="hidden" name="parent_id" value="">
            <input type="text" name="content" placeholder="Escreva um comentário..." required>
            <button type="submit">Enviar</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Entrar</a> ou <a href="register.php">Registrar-se</a> para comentar.</p>
    <?php endif; ?>

    <hr>

    <?php renderComments(); ?>
</body>
</html>

