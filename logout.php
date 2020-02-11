<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location: http://elvis.com.tw/boardcomments/index.php");
?>