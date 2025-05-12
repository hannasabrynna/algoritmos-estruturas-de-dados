<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit;
    } else {
        echo "Credenciais inválidas.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/style.css">
    <title>CodeWave</title>
</head>

<body id="login-body">
    <main>
        <section class="container">
            <div class="form-box" id="login-form">
                <h1 class="login-title">Entrar</h1>
                <form method="post">
                    <input name="email" placeholder="Email" required type="email">
                    <input name="password" placeholder="Senha" required type="password">
                    <button type="submit">Entrar</button>
                </form>
                <p id="login-link">Não possui uma conta? <a href="/register.php">Cadastre-se </a></p>
            </div>
        </section>
    </main>
</body>

</html>