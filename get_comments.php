<?php
    include_once('./conn.php');

    $page = $_POST["page"];

    $sql = "SELECT * FROM board_1 WHERE parent_id=0 ORDER BY id DESC limit " . ($page-1)*10 . ", 10";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $res[] = $row;
        }
        $arr = json_encode(['data' => $res]);
        echo $arr;
    } else {
        $arr = [];
    }

?>