<?php
    session_start();
    include_once('./conn.php');
    include_once('./utils.php');
    $is_error = false;
    if (isset($_POST["nickname"]) && isset($_POST["account"]) && isset($_POST["password"])) {
        $nickname = $_POST["nickname"];
        $account = $_POST["account"];
        $password = $_POST["password"];
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        if (!empty($_POST["nickname"]) && !empty($_POST["account"]) && !empty($_POST["password"])) {
            $stmt = $conn->prepare("INSERT INTO board_register (nickname, account, password) VALUES (?,?,?)");
            $stmt->bind_param("sss", $nickname, $account, $hash_password);
            if ($stmt->execute()) {
              $result = $stmt->get_result();
              $_SESSION['nickname'] = $nickname;
            }
            header("Location: http://elvis.com.tw/boardcomments/index.php");
        } else {
            $is_error = true;
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
  <title>Board Comments</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./style.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>

<body>
  <div>
    <div class="navbar">
      <div class="nav-title margin-left-15">Message Board</div>
      <div>
        <ul class="nav-items">
          <a href="./login.php" class="nav-item">登入</a>
        </ul>
      </div>
    </div>
    <div class="container">
      <div class="title">Register Board Account</div>

      <form class="register padding-top-30" method="POST" action="register.php" >
        <div>暱稱：
            <input id="nickname" name="nickname" type="text">
        </div>
        <div class="padding-top-30">帳號：
            <input id="account" name="account" placeholder="Account" type="text">
        </div>
        <div class="padding-top-30">密碼：
            <input id="password" name="password" placeholder="Password" type="password">
        </div>
        <div class="padding-top-30 padding-bottom-30">
          <input class="board-btn" type="submit" value="submit" />
        </div>
        <?php
            if ($is_error) {
                echo '<div>帳號/密碼/暱稱，有誤</div>';
            }
        ?>
      </form>

    </div>
  </div>
</body>

</html>