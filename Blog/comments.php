<?php
require 'db.php';

function renderComments($parentId = null, $level = 0)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT c.*, u.name AS user_name FROM comments c JOIN users u ON c.user_id = u.id
                          WHERE c.parent_id " . ($parentId === null ? "IS NULL" : "= :parent_id") . 
                          " ORDER BY c.created_at ASC");

    if ($parentId !== null) {
        $stmt->bindValue(':parent_id', $parentId);
    }
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($comments) {
        echo "<ul style='margin-left: " . ($level * 20) . "px'>";
        foreach ($comments as $comment) {
            echo "<li>";
            echo "<p><strong>{$comment['user_name']}</strong>: " . htmlspecialchars($comment['content']) . "</p>";
            echo "<form method='post' action='add_comment.php'>
                    <input type='hidden' name='parent_id' value='{$comment['id']}'>
                    <input type='text' name='content' placeholder='Responder...' required>
                    <button type='submit'>Responder</button>
                  </form>";
            renderComments($comment['id'], $level + 1);
            echo "</li>";
        }
        echo "</ul>";
    }
}


?>


