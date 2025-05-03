<?php
require 'auth.php';
include 'comments.php';
$user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/style.css">
    <title>CodeWave</title>
</head>

<body>
    <header>
        <h1>CodeWave</h1>
        <?php if ($user): ?>
            <p><strong><?= htmlspecialchars($user['name']) ?></strong>
                <a href="logout.php">Sair</a>
    </header>

    <main>
        <section>
            <article class="post">

                <div class="card-add-comment">
                    <form method="post" action="add_comments.php">
                        <input type="hidden" name="parent_id" value="">
                        <input type="text" name="content" placeholder="Adicione seu comentario aqui..." required>
                        <button type="submit">Enviar</button>
                    </form>
                </div>

                <div class="user-information">
                    <img src="./public/images/user-photo.png" alt="user-photo">
                    <p><strong>CodeWave</strong>
                </div>

                <div>
                    <img id="image-post" src="public/images/code-php.png" alt="Código em PHP">
                </div>

                <div>
                    <hr>
                    <?php renderComments(); ?>
                </div>
            </article>

        </section>
    <?php else: ?>
        <div id="container-btn">
            <a id="login-btn" href="login.php">Entrar</a>
            <a id="register-btn" href="register.php">Cadastre-se</a></p>
        </div>
    <?php endif; ?>
    </main>
</body>

</html>