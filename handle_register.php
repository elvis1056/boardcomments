<?php

    include_once('./conn.php');

    $nickname = $_POST["nickname"];
    $account = $_POST["account"];
    $password = $_POST["password"];
    $hash_password = password_hash($password, PASSWORD_DEFAULT);
    
    if ($nickname !== '' && $account !== '' && $password !=='') {
        $sql = "INSERT INTO board_register (nickname, account, password) VALUES ('$nickname', '$account', '$hash_password')";
        $stmt = $conn->prepare($sql);
        $stmt = bind_param("sss", $nickname, $account, $hash_password);
        if ($stmt->execute()) {
            header("Location: index.php");
        } else {
            $sql = "INSERT INTO board_register (nickname, account, password) VALUES ('$nickname', '$account', '$hash_password')";
            echo 'error: ' . $sql . '<br>' . $conn->error;
        }
    } else {
        $sql = "INSERT INTO board_register (nickname, account, password) VALUES ('$nickname', '$account', '$hash_password')";
        echo 'error: ' . $sql . '<br>' . $conn->error;
    }

?>