<?php

    include_once('./conn.php');

    $sql_countPage = "SELECT count(*) as count FROM board_1 WHERE parent_id=0";
    $result_countPage = $conn->query($sql_countPage);

    if ($result_countPage) {
        $count = $result_countPage->fetch_assoc()['count'];
        $limit_page = 10;
        $total_page = ceil($count / $limit_page);
        echo $total_page;
    } else {
        echo 1;
    }

?>