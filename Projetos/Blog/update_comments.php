<?php
require 'db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$commentId = $_POST['comment_id'] ?? null;
$newContent = $_POST['content'] ?? null;


if ($commentId && $newContent) {
    $stmt = $pdo->prepare("UPDATE comments SET content = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$newContent, $commentId, $_SESSION['user_id']]);
} else {
    die("Comentário não encontrado ou conteúdo inválido.");
}

header("Location: index.php");
exit;

?>