<?php
    include_once('./conn.php');

    $comment = $_POST['comment'];
    $parent_id = $_POST['parent_id'];

    $sql = "SELECT * FROM board_1 WHERE parent_id='". $parent_id . "'and content='" . $comment ."'";
    $result = $conn->query($sql);

    if ($result) {
        while ($sub_row = $result->fetch_assoc()) {
            $res[] = $sub_row;
        }
        $arr = json_encode(['data' => $res]);
        echo $arr;
    } else {
        echo $arr = [];
    }

?>