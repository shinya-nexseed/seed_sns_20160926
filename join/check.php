<?php
    // セッションの設定
    session_start();

 ?>

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
















