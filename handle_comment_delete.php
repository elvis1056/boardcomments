<?php
    include_once('./conn.php');
    include_once('./check_login.php');

    $comment_id = $_POST['comment_id'];
    
    $sql = "DELETE FROM board_1 WHERE (id=? or parent_id=?) AND nickname=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $comment_id, $comment_id, $username);
    $stmt->execute();

    if ($stmt->execute()) {
        echo json_encode(array(
            "result" => "success",
            "message" => "刪除成功！"
            // successfully deleted
        ));
    } else {
        echo json_encode(array(
            "result" => "fail",
            "message" => "刪除失敗！請稍後重新再試！"
            // deleted failed
        ));
    }

?>