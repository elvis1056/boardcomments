<?php
    session_start();
    include_once('./conn.php');
    include_once('./utils.php');
    // if (isset($_SESSION['nickname']) && !empty($_SESSION['nickname'])) {
        $username = $_SESSION['nickname'];
    // }
?>