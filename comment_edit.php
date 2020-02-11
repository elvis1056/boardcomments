<?php
    include_once('./conn.php');
    include_once('./check_login.php');
?>
<!DOCTYPE html>
<html>

<head>
  <title>week13.hw</title>
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
          <?php
            if ($username) {
          ?>
              <a href="./logout.php" class="nav-item">登出</a>
          <?php
            } else {
          ?>
              <a href="./login.php" class="nav-item">登入</a>
              <a href="./register.php" class="nav-item">註冊</a>
          <?php
            }
          ?>
        </ul>
      </div>
    </div>
    <?php
        $id = $_GET['comment_id'];
        $sql = "SELECT * FROM board_1 WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc()
    ?>

    <div class="container">
      <div class="title">Edit Your Comment</div>
      <form class="board-write" method="POST" action="./handle_comment_edit.php">
        <div class="board-name"><?php echo escape($username); ?></div>
        <textarea class="board-write-text" name="comment" id="" cols="30" rows="10"><?php echo escape($row['content']); ?></textarea>
        <div class="padding-top-30 padding-bottom-30">
        <input type="hidden" name="id" value="<?php echo $id?>">
        <input class="board-btn" type="submit" value="submit" />
        </div>
      </form>
    </div>  
    <?php
        }
    ?>
</body>

</html>