<?php
    // セッションの設定
    session_start();
    require('../dbconnect.php');

    // 各入力欄のvalueの初期値を定義
    $nick_name = '';
    $email = '';
    $password = '';

    // フォームからデータが送信された場合
    if (!empty($_POST)) {
        $nick_name = $_POST['nick_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // ニックネーム未入力チェック
        if ($_POST['nick_name'] == '') {
            $error['nick_name'] = 'blank';
        }

        // メールアドレス未入力チェック
        if ($_POST['email'] == '') {
            $error['email'] = 'blank';
        }

        // パスワード未入力チェック
        if ($_POST['password'] == '') {
            $error['password'] = 'blank';
        } elseif (strlen($_POST['password']) < 4) {
            $error['password'] = 'length';
        }

        // 画像ファイルの拡張子チェック
        $fileName = $_FILES['picture_path']['name'];
        // $_FILES['inputタグのname']['$_FILES内で決められたキー']
        if (!empty($fileName)) {
            $ext = substr($fileName, -3);
            if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
                $error['picture_path'] = 'type';
            }
        }

        // メールアドレスの重複チェック
        if (empty($error)) {
            $sql = sprintf('SELECT COUNT(*) AS cnt FROM `members`
                           WHERE `email`="%s"',
                           mysqli_real_escape_string($db, $email)
                           );
            // p246のコード
            // $recordはObject型 (このままでは使えないデータ)
            $record = mysqli_query($db, $sql) or die(mysqli_error($db));
            // fetchしてArray型に変換する
            $table = mysqli_fetch_assoc($record);
            if ($table['cnt'] > 0) {
                $error['email'] = 'duplicate';
            }
        }

        // エラーがなかった場合の処理
        if (empty($error)) {
            // 画像をアップロードする
            $picture_path = date('YmdHis') . $_FILES['picture_path']['name'];
            move_uploaded_file($_FILES['picture_path']['tmp_name'], '../member_picture/' . $picture_path);

            // セッションに値を保存
            $_SESSION['join'] = $_POST;
            $_SESSION['join']['picture_path'] = $picture_path;
            header('Location: check.php');
            exit();
        }

    }

    // 書き直し処理
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite') {
        $_POST = $_SESSION['join'];
        $nick_name = $_POST['nick_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $error['rewrite'] = true;
    }
 ?>

<!--
  input type="file"を使用する場合はformタグにenctypeの指定が必須
  $_FILESが生成される条件は
    ①enctypeが指定されている
    ②input type="file"がある
 -->


<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SeedSNS</title>

    <!-- Bootstrap -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/form.css" rel="stylesheet">
    <link href="../assets/css/timeline.css" rel="stylesheet">
    <link href="../assets/css/main.css" rel="stylesheet">
    <!--
      designフォルダ内では2つパスの位置を戻ってからcssにアクセスしていることに注意！
     -->


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="index.html"><span class="strong-title"><i class="fa fa-twitter-square"></i> Seed SNS</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 content-margin-top">
        <legend>会員登録</legend>
        <form method="post" action="index.php" class="form-horizontal" role="form" enctype="multipart/form-data">
          <!-- ニックネーム -->
          <div class="form-group">
            <label class="col-sm-4 control-label">ニックネーム</label>
            <div class="col-sm-8">
              <input type="text" name="nick_name" class="form-control" placeholder="例： Seed kun" value="<?php echo $nick_name; ?>">
              <?php if(isset($error['nick_name']) && $error['nick_name'] == 'blank'): ?>
                <p style="color:red;">* ニックネームを入力してください</p>
              <?php endif; ?>
            </div>
          </div>
          <!-- メールアドレス -->
          <div class="form-group">
            <label class="col-sm-4 control-label">メールアドレス</label>
            <div class="col-sm-8">
              <input type="email" name="email" class="form-control" placeholder="例： seed@nex.com" value="<?php echo $email; ?>">
              <?php if(isset($error['email']) && $error['email'] == 'blank'): ?>
                <p style="color:red;">* メールアドレスを入力してください</p>
              <?php endif; ?>
              <!-- p247のコード (ifの条件、今までのコードと合わせる必要あり) -->
              <?php if(isset($error['email']) && $error['email'] == 'duplicate'): ?>
                <p style="color:red;">* 指定されたメールアドレスは既に登録されています</p>
              <?php endif; ?>
            </div>
          </div>
          <!-- パスワード -->
          <div class="form-group">
            <label class="col-sm-4 control-label">パスワード</label>
            <div class="col-sm-8">
              <input type="password" name="password" class="form-control" placeholder="" value="<?php echo $password; ?>">
              <?php if(isset($error['password']) && $error['password'] == 'blank'): ?>
                <p style="color:red;">* パスワードを入力してください</p>
              <?php endif; ?>
              <?php if(isset($error['password']) && $error['password'] == 'length'): ?>
                <p style="color:red;">* パスワードは４文字以上で入力してください</p>
              <?php endif; ?>
            </div>
          </div>
          <!-- プロフィール写真 -->
          <div class="form-group">
            <label class="col-sm-4 control-label">プロフィール写真</label>
            <div class="col-sm-8">
              <input type="file" name="picture_path" class="form-control">
              <?php if(isset($error['picture_path']) && $error['picture_path'] == 'type'): ?>
                <p style="color:red;">* プロフィール画像は「jpg」「gif」「png」の画像を指定してください</p>
              <?php endif; ?>
              <?php if(!empty($error)): ?>
                <p style="color:red;">* 画像を再選択してください</p>
              <?php endif; ?>
            </div>
          </div>

          <input type="submit" class="btn btn-default" value="確認画面へ">
        </form>
      </div>
    </div>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

