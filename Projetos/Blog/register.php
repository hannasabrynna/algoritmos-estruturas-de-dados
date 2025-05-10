<?php
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password_raw = $_POST['password'] ?? '';

    // Validações básicas
    if ($name === '' || $email === '' || $password_raw === '') {
        $errors[] = 'Todos os campos são obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email inválido.';
    } elseif (strlen($password_raw) < 6) {
        $errors[] = 'A senha deve ter pelo menos 6 caracteres.';
    } else {
        // Verifica se o email já existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Este email já está cadastrado.';
        }
    }

    // Se não houver erros, insere no banco
    if (empty($errors)) {
        $password = password_hash($password_raw, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $password])) {
            header("Location: login.php");
            exit;
        } else {
            $errors[] = 'Erro ao salvar os dados. Tente novamente.';
        }
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

<body>
    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <section class="container">
        <div class="form-box" id="register-form">
            <h1 class="register-title">Cadastre-se</h1>
            <form method="post">
                <input name="name" placeholder="Nome" required>
                <input name="email" placeholder="Email" required type="email">
                <input name="password" placeholder="Senha" required type="password">
                <button type="submit">Cadastre-se</button>
            </form>
            <p id="register-link">Já possui uma conta? <a href="/login.php">Entrar</a></p>
        </div>
    </section>
</body>

</html>