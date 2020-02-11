<?php
    include_once('./conn.php');

    $comment = $_POST['comment'];
    $parent_id = $_POST['parent_id'];

    $sql = "SELECT * FROM board_1 WHERE parent_id=0 and content='" . $comment . "'";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $res[] = $row;
        }
        $arr = json_encode(['data' => $res]);
        echo $arr;
    } else {
        echo $arr = [];
    }

?>