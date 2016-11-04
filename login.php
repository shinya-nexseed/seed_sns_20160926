<?php
    session_start();
    require('dbconnect.php');

    // 初期値
    $email = '';
    $password = '';

    // 自動ログイン処理
    if (isset($_COOKIE['email']) && $_COOKIE['email'] != '') {
        $_POST['email'] = $_COOKIE['email'];
        $_POST['password'] = $_COOKIE['password'];
        $_POST['save'] = 'on';
    }

    // ログインボタンクリック時
    if (!empty($_POST)) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($email != '' && $password != '') {
            // ログイン処理
            $sql = sprintf('SELECT * FROM `members` WHERE `email`="%s" AND `password`="%s"',
                           mysqli_real_escape_string($db, $email),
                           mysqli_real_escape_string($db, sha1($password))
                  );
            $record = mysqli_query($db, $sql) or die(mysqli_error($db));

            if ($table = mysqli_fetch_assoc($record)) {
                // ログイン成功
                $_SESSION['id'] = $table['member_id'];
                $_SESSION['time'] = time();

                // もし自動ログインチェックが入っていればCookieにデータを保存する
                if ($_POST['save'] == 'on') {
                    setcookie('email', $email, time() + 60*60*24*14);
                    setcookie('password', $password, time() + 60*60*24*14);
                }

                header('Location: index.php');
                exit();
            } else {
                // ログイン失敗
                $error['login'] = 'failed';
            }

        } else {
            // 入力必須エラー
            $error['login'] = 'blank';
        }
    }
 ?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SeedSNS</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">


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
        <legend>ログイン</legend>
        <form method="post" action="" class="form-horizontal" role="form">
          <!-- メールアドレス -->
          <div class="form-group">
            <label class="col-sm-4 control-label">メールアドレス</label>
            <div class="col-sm-8">
              <input type="text" name="email" class="form-control" placeholder="例： seed@nex.com" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">
              <?php if(isset($error['login']) && $error['login'] == 'blank'): ?>
                <p class="error">* メールアドレスとパスワードを入力してください</p>
              <?php endif; ?>
              <?php if(isset($error['login']) && $error['login'] == 'failed'): ?>
                <p class="error">* ログインに失敗しました。正しく情報を入力してください</p>
              <?php endif; ?>
            </div>
          </div>
          <!-- パスワード -->
          <div class="form-group">
            <label class="col-sm-4 control-label">パスワード</label>
            <div class="col-sm-8">
              <input type="password" name="password" class="form-control" placeholder="" value="<?php echo htmlspecialchars($password, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
          </div>
          <!-- 自動ログイン設定のチェックボックス -->
          <div class="form-group">
            <label for="save" class="col-sm-4 control-label">自動ログイン</label>
            <div class="col-sm-8">
              <input type="checkbox" id="save" name="save" value="on">
            </div>
          </div>
          <input type="submit" class="btn btn-default" value="ログイン"> | <a href="join/index.php" class="btn btn-success">会員登録</a>
        </form>
      </div>
    </div>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>




