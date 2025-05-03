<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $password]);

    header("Location: login.php");
    exit;
}
?>

<form method="post">
    <input name="name" placeholder="Nome" required>
    <input name="email" placeholder="Email" required type="email">
    <input name="password" placeholder="Senha" required type="password">
    <button type="submit">Registrar</button>
</form>

<?php