<?php
    include_once('./conn.php');
    
    $id = $_POST['id'];

    $sub_sql = "SELECT * FROM board_1 WHERE parent_id=" . $id;
    $sub_result = $conn->query($sub_sql);

    if ($sub_result) {
        while ($sub_row = $sub_result->fetch_assoc()) {
            $res[] = $sub_row;
        }
        $arrsub = json_encode(['data_sub' => $res]);
        echo $arrsub;
    } else {
        $arrsub = [];
    }

?>