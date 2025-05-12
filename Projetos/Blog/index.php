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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>CodeWave</title>
</head>

<body>
    <header>
        <nav>
            <div class="navbar-container">
                <h1 class="site-name">CodeWave</h1>
                <div class="user-container">
                    <?php if ($user): ?>
                        <div class="user-profile">
                            <div class="user-icon">
                                <img src="./public/images/user-photo.png" alt="user-photo">
                            </div>
                            <div class="user-info">
                                <p><strong><?= htmlspecialchars($user['name']) ?></strong>
                                    <a href="logout.php">Sair</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <?php if ($user): ?>
            <section class="post-container">
                <article class="post">
                    <div class="post-header">
                        <div class="left">
                            <div class="user-photo">
                                <img src="./public/images/user-photo.png" alt="user-photo">
                            </div>
                            <span>DevAna</span>
                        </div>

                        <div class="right">
                            <button class="follow-btn">Seguir</button>
                        </div>
                    </div>

                    <div class="post-image">
                        <img src="public/images/code-php.png" alt="Imagem de código em PHP">
                    </div>

                    <div class="post-icons">
                        <i class="fa fa-heart-o"></i>
                        <i class="fa fa-comment-o"></i>
                        <i class="fa fa-paper-plane-o"></i>
                    </div>

                    <div class="post-likes">Curtido por <strong>dev.php</strong> e <strong>outras pessoas</strong></div>

                    <div class="post-description">
                        <strong>devana</strong> Você já ouviu falar em funções recursivas? Em PHP, recursividade acontece quando uma função chama a si mesma para resolver um problema em partes menores. É muito útil para tarefas como percorrer diretórios, resolver algoritmos matemáticos (como fatorial e Fibonacci) ou manipular estruturas em árvore.
                    </div>
                    <div class="card-add-comment">
                        <form method="post" action="add_comments.php">
                            <input type="hidden" name="parent_id" value="">
                            <input type="text" name="content" placeholder="Adicionar comentário" required>
                            <button type="submit">Enviar</button>
                        </form>
                        <?php renderComments(); ?>
                    </div>
                </article>

            </section>
        <?php else: ?>
            <section>
                <div id="container-btn">
                    <a id="login-btn" href="login.php">Entrar</a>
                    <a id="register-btn" href="register.php">Cadastre-se</a></p>
                </div>
            </section>
        <?php endif; ?>
    </main>
</body>

</html>