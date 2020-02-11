<?php
    include_once('./conn.php');
    include_once('./check_login.php');

    $id = $_POST["id"];
    $comment = $_POST["comment"];

    $sql = "UPDATE board_1 SET content=? WHERE id=? AND nickname=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $comment, $id, $username);
    
    if ($stmt->execute()) {
        echo '{"success": true}';
    } else {
        echo '{"success": false}';
    }
?>