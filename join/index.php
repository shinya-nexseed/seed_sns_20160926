<?php
    // セッションの設定
    session_start();

    // 各入力欄のvalueの初期値を定義
    $nick_name = '';

    // フォームからデータが送信された場合
    if (!empty($_POST)) {
        $nick_name = $_POST['nick_name'];

        // ニックネーム未入力チェック
        if ($_POST['nick_name'] == '') {
            $error['nick_name'] = 'blank';
        }

        // メールアドレス未入力チェック
        if ($_POST['email'] == '') {
            $error['email'] = 'blank';
        }

        // エラーがなかった場合の処理
        if (empty($error)) {
            header('Location: check.php');
            exit();
        }

    }


 ?>

<form method="post" action="index.php">
  <input type="text" name="nick_name" value="<?php echo $nick_name; ?>"><br>
  <?php if(isset($error['nick_name']) && $error['nick_name'] == 'blank'): ?>
    <p style="color:red;">* ニックネームを入力してください</p>
  <?php endif; ?>
  <input type="email" name="email"><br>
  <?php if(isset($error['email']) && $error['email'] == 'blank'): ?>
    <p style="color:red;">* メールアドレスを入力してください</p>
  <?php endif; ?>
  <input type="submit" value="確認画面へ">
</form>







