<?php
    function escape($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    function renderDeleteBtn($id) {
        return '
        <div class="comment-delete">
            <input type="hidden" name="comment_id" value='. $id .' > 
            刪除
        </div>
        ';
    }

    function renderEditBtn($id) {
        return '
        <form method="GET" action="./comment_edit.php">
            <input type="hidden" name="comment_id" value='. $id .' > 
            <input class="comment-edit" type="submit" value="編輯" />
        </form>
        ';
    }
?>