<?php
require 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

function formComment($comment)
{
    //deletar comentário
    if ($_SESSION['user_id'] == $comment['user_id']) {
        echo "<form method='post' action='delete_comment.php' style='margin-top:10px'>
                            <input type='hidden' name='comment_id' value='{$comment['id']}'>
                            <button type='submit' style='border: none; color: #353535; font-size: 14px; font-weight:bold; cursor: pointer; margin-bottom: 10px; padding: 5px 10px;'>Excluir</button>
                          </form>";

        // Editar comentário
        echo "<button onclick=\"showEditForm({$comment['id']})\" style='border: none; color:#353535; font-size: 14px; font-weight:bold; cursor: pointer; padding: 5px 10px;'>Editar</button>";

        echo "<div id='edit-form-{$comment['id']}' style='display:none; margin-top:10px;'>
                    <form method='post' action='update_comments.php'>
                    <input type='hidden' name='comment_id' value='{$comment['id']}'>
                    <textarea name='content' required style='width:100%; height:60px; margin-bottom: 10px; border: none; border-bottom: 1px solid #ccc; border-radius: 5px; padding: 8px; width: 80%; outline: none; font-size: 14px;'>" . htmlspecialchars($comment['content']) . "</textarea>
                    <br>
                    <button type='submit' style='cursor: pointer;'>Salvar</button>
                    <button type='button' style='cursor: pointer;' onclick='cancelEditForm({$comment['id']})'>Cancelar</button>
                    </form>
                    </div>";
    }

    // Formulário de resposta
    echo "<form method='post' action='add_comments.php' style='margin-top:10px'>
                        <input type='hidden' name='parent_id' value='{$comment['id']}'>
                        <div style='position: relative; width: 100%;'> 
                            <input type='text' style='border: none; border-bottom: 1px solid #ccc; border-radius: 5px; padding: 8px 70px 8px 10px; width: 100%; height: 50px; outline: none; font-size: 14px;'>
                            <button type='submit' style='position: absolute;  top: 50%; right: 10px; transform: translateY(-50%); background: none; color: #4B1D74; border: none; font-size: 14px; font-weight: medium;cursor: pointer; transition: background-color 0.3s ease, transform 0.2s ease;cursor: pointer;'>Responder</button>
                        </div>
                      </form>";
}

#### Funçâo para renderizar comentários (recursiva) ####
function renderComments($parentId = null, $level = 0, $limit = 3, &$count = 0, $branchId = null, &$totalInBranch = 0)
{
    global $pdo;

    // Buscar todos os comentários pai
    if ($level === 0 && $parentId === null) {
        $stmt = $pdo->query("SELECT c.*, u.name AS user_name FROM comments c 
                            JOIN users u ON c.user_id = u.id 
                            WHERE c.parent_id IS NULL 
                            ORDER BY c.created_at DESC");
        $parents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($parents as $parent) {
            echo "<div style='padding: 10px 0 10px 0; margin: 0 0 20px 0;'>";
            echo "<p><strong>{$parent['user_name']}</strong> " . nl2br(htmlspecialchars($parent['content'])) . "</p>";
            echo "<p><small>" . date('H:i', strtotime($parent['created_at'])) . "</small></p>";

            //lista os comentários
            formComment($parent);

            // Variáveis locais para cada pai
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

    // Buscar Comentários filhos
    $stmt = $pdo->prepare("SELECT c.*, u.name AS user_name FROM comments c 
                          JOIN users u ON c.user_id = u.id
                          WHERE c.parent_id = :parent_id
                          ORDER BY c.created_at DESC");
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

            //lista os comentários
            formComment($comment);

            $count++;
            renderComments($comment['id'], $level + 1, $limit, $count, $branchId, $totalInBranch);
            echo "</div></li>";
        }
        echo "</ul>";
    }
}

#### Função para contar comentários (Recusiva) ####
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

    function showEditForm(commentId) {
        document.getElementById('edit-form-' + commentId).style.display = 'block';
    }

    function cancelEditForm(commentId) {
        document.getElementById('edit-form-' + commentId).style.display = 'none';
    }
</script>