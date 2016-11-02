<?php
    // セッションの設定
    session_start();
    require('../dbconnect.php');
    // $_SESSION = array(); // セッションのデバッグでよく使う。セッションの中身を初期化

    // セッションにデータがなければindex.phpへ遷移する
    if (!isset($_SESSION['join'])) {
        header('Location: index.php');
        exit();
    }

    // もし登録ボタンが押されたら
    if (!empty($_POST)) {
        // 登録処理
        $sql = sprintf('INSERT INTO `members` SET
    `nick_name`="%s",
    `email`="%s",
    `password`="%s",
    `picture_path`="%s",
    `created`=NOW()',
    mysqli_real_escape_string($db, $_SESSION['join']['nick_name']),
    mysqli_real_escape_string($db, $_SESSION['join']['email']),
    mysqli_real_escape_string($db, sha1($_SESSION['join']['password'])),
    mysqli_real_escape_string($db, $_SESSION['join']['picture_path'])
    );
        mysqli_query($db, $sql) or die(mysqli_error($db));
        unset($_SESSION['join']);

        header('Location: thanks.php');
        exit();
    }

 ?>

<form action="check.php" method="post">
  <input type="hidden" name="action" value="submit">
  <div>
    ニックネーム<br>
    <?php echo $_SESSION['join']['nick_name']; ?>
  </div>

  <div>
    メールアドレス<br>
    <?php echo $_SESSION['join']['email']; ?>
  </div>

  <div>
    パスワード<br>
    <?php echo $_SESSION['join']['password']; ?>
  </div>

  <div>
    プロフィール画像<br>
    <img src="../member_picture/<?php echo $_SESSION['join']['picture_path']; ?>" width="100">
  </div>

  <div>
    <a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a>
  </div>
  <input type="submit" value="登録する">
</form>















