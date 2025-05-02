<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado.");
}

$content = $_POST['content'] ?? '';
$parentId = $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;
$userId = $_SESSION['user_id'];

if ($content) {
    $stmt = $pdo->prepare("INSERT INTO comments (parent_id, content, user_id) VALUES (?, ?, ?)");
    $stmt->execute([$parentId, $content, $userId]);
}

header("Location: index.php");
exit;
