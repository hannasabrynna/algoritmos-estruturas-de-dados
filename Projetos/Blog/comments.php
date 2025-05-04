<?php
require 'db.php';


session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

function renderComments($parentId = null, $level = 0, $limit = 3, &$count = 0, $branchId = null, &$totalInBranch = 0)
{
    global $pdo;

    if ($level === 0 && $parentId === null) {
        // Buscar todos os comentários pai
        $stmt = $pdo->query("SELECT c.*, u.name AS user_name FROM comments c 
                            JOIN users u ON c.user_id = u.id 
                            WHERE c.parent_id IS NULL 
                            ORDER BY c.created_at ASC");
        $parents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($parents as $parent) {
            echo "<div style='border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 5px;'>";
            echo "<p><strong>{$parent['user_name']}</strong>: " . nl2br(htmlspecialchars($parent['content'])) . "</p>";
            echo "<p><small>Postado em: " . date('d/m/Y H:i', strtotime($parent['created_at'])) . "</small></p>";

            //deletar comentário
            if ($_SESSION['user_id'] == $parent['user_id']) {
                echo "<form method='post' action='delete_comment.php' style='margin-top:10px'>
                        <input type='hidden' name='comment_id' value='{$parent['id']}'>
                        <button type='submit' style='padding:5px 10px;'>Deletar</button>
                      </form>";
            }

            // Formulário de resposta
            echo "<form method='post' action='add_comments.php' style='margin-top:10px'>
                    <input type='hidden' name='parent_id' value='{$parent['id']}'>
                    <input type='text' name='content' placeholder='Responder...' required style='width:70%; padding:5px;'>
                    <button type='submit' style='padding:5px 10px;'>Responder</button>
                  </form>";

            // ✅ Variáveis locais para cada pai
            $branchId = "branch_" . $parent['id'];
            $countLocal = 0;
            $totalLocal = countBranchComments($parent['id']);

            echo "<div id='$branchId'>";
            renderComments($parent['id'], 1, $limit, $countLocal, $branchId, $totalLocal);
            echo "</div>";

            if ($totalLocal > $limit) {
                echo "<button onclick=\"toggleComments('$branchId', this)\" style='margin-top: 10px;'>Mostrar mais</button>";
            }

            echo "</div>";
        }

        return;
    }

    // Comentários filhos/netos
    $stmt = $pdo->prepare("SELECT c.*, u.name AS user_name FROM comments c 
                          JOIN users u ON c.user_id = u.id
                          WHERE c.parent_id = :parent_id
                          ORDER BY c.created_at ASC");
    $stmt->execute(['parent_id' => $parentId]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($comments) {
        echo "<ul style='margin-left: " . ($level * 20) . "px; list-style-type:none; padding-left:0;'>";
        foreach ($comments as $comment) {
            $isHidden = $count >= $limit;
            $style = $isHidden ? "style='display:none'" : "";
            $class = $isHidden ? "hidden-comment-$branchId" : "";

            echo "<li class='$class' $style>";
            echo "<div style='border-left: 3px solid #ddd; padding-left: 10px; margin-top: 10px;'>";
            echo "<p><strong>{$comment['user_name']}</strong>: " . nl2br(htmlspecialchars($comment['content'])) . "</p>";
        
            //deletar comentário
            if ($_SESSION['user_id'] == $comment['user_id']) {
                echo "<form method='post' action='delete_comment.php' style='margin-top:10px'>
                        <input type='hidden' name='comment_id' value='{$comment['id']}'>
                        <button type='submit' style='padding:5px 10px;'>Deletar</button>
                      </form>";
            }

            // Formulário de resposta
            echo "<form method='post' action='add_comments.php' style='margin-top:10px'>
                    <input type='hidden' name='parent_id' value='{$comment['id']}'>
                    <input type='text' name='content' placeholder='Responder...' required style='width:70%; padding:5px;'>
                    <button type='submit' style='padding:5px 10px;'>Responder</button>
                  </form>";

            $count++;
            renderComments($comment['id'], $level + 1, $limit, $count, $branchId, $totalInBranch);
            echo "</div></li>";
        }
        echo "</ul>";
    }
}

function countBranchComments($parentId)
{
    global $pdo;
    $count = 0;
    $stmt = $pdo->prepare("SELECT id FROM comments WHERE parent_id = :parent_id");
    $stmt->execute(['parent_id' => $parentId]);
    $children = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($children as $child) {
        $count++;
        $count += countBranchComments($child['id']);
    }

    return $count;
}
?>

<script>
function toggleComments(branchId, button) {
    const hidden = document.querySelectorAll('.hidden-comment-' + branchId);
    const isVisible = hidden[0]?.style.display === 'block';

    hidden.forEach(el => {
        el.style.display = isVisible ? 'none' : 'block';
    });

    button.textContent = isVisible ? 'Mostrar mais' : 'Mostrar menos';
}
</script>
