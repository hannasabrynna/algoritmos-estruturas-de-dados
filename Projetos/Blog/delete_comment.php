<?php
require 'db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

//deletar comentário
$commentId = $_POST['comment_id'] ?? null;
if ($commentId) {
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
    $stmt->execute([$commentId, $_SESSION['user_id']]);
}
else {
    die("Comentário não encontrado ou você não tem permissão para deletá-lo.");
}


header("Location: index.php");
exit;


?>