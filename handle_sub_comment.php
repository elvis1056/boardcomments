<?php
    include_once('./conn.php');
    include_once('./check_login.php');

    $parent_id = $_POST["parent_id"];
    $sub_comment = $_POST["sub_comment"];

    $stmt = $conn->prepare("INSERT INTO board_1(parent_id, nickname, content) VALUES (?,?,?)");
    $stmt->bind_param("iss", $parent_id, $username, $sub_comment);

    if ($stmt->execute()) {
        echo '{"success": true}';
    } else {
        echo '{"success": false}';
    }

?>