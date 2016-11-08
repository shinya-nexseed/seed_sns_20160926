<?php
    session_start();
    require('dbconnect.php');

    // ログイン中か判定するための条件
      // 1. セッションにidが入っていること
      // 2. 最後の行動から1時間以内であること
    if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
        // ログインしていると判定し、idを元にログインユーザーの情報を取得
        $_SESSION['time'] = time();

        $sql = sprintf('SELECT * FROM `members` WHERE `member_id`=%d',
                       mysqli_real_escape_string($db, $_SESSION['id'])
                       );
        $record = mysqli_query($db, $sql) or die(mysqli_error($db));
        $member = mysqli_fetch_assoc($record);
    } else {
        // ログインしていないと判定し、強制的に別ページへ遷移
        header('Location: login.php');
        exit();
    }

    // つぶやきを登録する処理 Create
    if (!empty($_POST)) {
        // つぶやきデータが入力されていれば
        if ($_POST['tweet'] != '') {
            $sql = sprintf('INSERT INTO `tweets`
                            SET `tweet`="%s",
                                `member_id`=%d,
                                `reply_tweet_id`=%d,
                                `created`=NOW()',
                            mysqli_real_escape_string($db, $_POST['tweet']),
                            mysqli_real_escape_string($db, $member['member_id']),
                            mysqli_real_escape_string($db, $_POST['reply_tweet_id'])
                          );
            mysqli_query($db, $sql) or die(mysqli_error($db));

            // リロード時重複登録を回避するため画面を再度読み込む
            header('Location: index.php');
            exit();
        }
    }

    // ページング処理
    $page = '';
    // パラメータ上のページ番号取得
    if (isset($_REQUEST['page'])) {
        $page = $_REQUEST['page'];
    }
    // パラメータにページ番号がない場合は、ページ番号を1にする
    if ($page == '') {
        $page = 1;
    }
    // 表示する正しいページの数値 (最小値) を設定
    $page = max($page, 1);
    $page = ceil($page / 1);
    // 必要なページ数を計算
    $sql = 'SELECT COUNT(*) AS cnt FROM `tweets`';
    $recordSet = mysqli_query($db, $sql) or die(mysqli_error($db));
    $table = mysqli_fetch_assoc($recordSet);
    // 5で割り切れない件数の場合は切り上げる
    $maxPage = ceil($table['cnt'] / 5);
    // 表示する正しいページ数の数値 (最大値) を設定
    $page = min($page, $maxPage);

    // ページに表示する件数だけ取得
    $start = ($page - 1) * 5;
    $start = max(0, $start);

    // つぶやきデータを取得する Read
    if (!empty($_GET['search_word'])) {
        // 検索の場合
        // sprintf関数とLIKE句を組み合わせる場合は、LIKEの%を2回繰り返す必要がある
        $sql = sprintf('SELECT m.`nick_name`, m.`picture_path`, t.*
                FROM `tweets` t, `members` m
                WHERE m.`member_id`=t.`member_id`
                AND t.`tweet` LIKE "%%%s%%"
                ORDER BY t.`created` DESC LIMIT %d, 5',
                mysqli_real_escape_string($db, $_GET['search_word']),
                $start
              );
    } else {
        // 普通にページを表示する場合
        $sql = sprintf('SELECT m.`nick_name`, m.`picture_path`, t.*
                FROM `tweets` t, `members` m
                WHERE m.`member_id`=t.`member_id`
                ORDER BY t.`created` DESC LIMIT %d, 5',
                $start
              );
    }

    $tweets = mysqli_query($db, $sql) or die(mysqli_error($db));

    // 返信処理
    $reply_tweet = '';
    if (isset($_REQUEST['res'])) {
        // Reが押されたtweetデータをDBから取得し、返信用の文字列を作成する
        $sql = sprintf('SELECT m.`nick_name`, t.`tweet`
                        FROM `tweets` t, `members` m
                        WHERE m.`member_id`=t.`member_id`
                        AND t.`tweet_id`=%d',
                    mysqli_real_escape_string($db, $_REQUEST['res'])
               );
        $record = mysqli_query($db, $sql) or die(mysqli_error($db));
        $table = mysqli_fetch_assoc($record);
        $reply_tweet = '@' . $table['nick_name'] . ': ' . $table['tweet'] . ' -> ';
    }

    // 正規表現を使ってつぶやき内のURLにリンクを設置する
    function makeLink($value) {
        return mb_ereg_replace('(https?)(://[[:alnum:]¥+¥$¥;¥?¥.%,!#~*/:@&=_-]+)', '<a href="\1\2" target="_blank">\1\2</a>', $value);
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
                <li><a href="logout.php">ログアウト</a></li>
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-4 content-margin-top">
        <legend>ようこそ<?php echo $member['nick_name']; ?>さん！</legend>
        <form method="post" action="" class="form-horizontal" role="form">
            <!-- つぶやき -->
            <div class="form-group">
              <label class="col-sm-4 control-label">つぶやき</label>
              <div class="col-sm-8">
                <textarea name="tweet" cols="50" rows="5" class="form-control" placeholder="例：Hello World!"><?php echo $reply_tweet; ?></textarea>
                <input type="hidden" name="reply_tweet_id" value="<?php echo $_REQUEST['res']; ?>">
              </div>
            </div>
          <ul class="paging">
            <input type="submit" class="btn btn-info" value="つぶやく">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <?php if($page > 1): ?>
                  <li><a href="index.php?page=<?php echo $page - 1; ?>" class="btn btn-default">前</a></li>
                <?php else: ?>
                  <li>前</li>
                <?php endif; ?>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <?php if($page < $maxPage): ?>
                  <li><a href="index.php?page=<?php echo $page + 1; ?>" class="btn btn-default">次</a></li>
                <?php else: ?>
                  <li>次</li>
                <?php endif; ?>
          </ul>
        </form>
      </div>

      <div class="col-md-8 content-margin-top">
        <!-- 検索ボックス -->
        <form action="index.php" method="get" class="form-horizontal">
          <select name="" id="">
            <option value="">ユーザー名</option>
            <option value="">つぶやき</option>
            <option value="">メールアドレス</option>
          </select>
          <input type="text" name="search_word">
          <!-- index.php?search_word=value -->
          <!-- $_GET['search_word'] -->
          <input type="submit" value="検索" class="btn btn-success btn-xs">
        </form>
        <?php while($tweet = mysqli_fetch_assoc($tweets)): ?>
          <div class="msg">
            <img src="member_picture/<?php echo $tweet['picture_path']; ?>" width="48" height="48">
            <p>
              <?php echo makeLink($tweet['tweet']); ?><span class="name"> (<?php echo $tweet['nick_name']; ?>) </span>
              [<a href="index.php?res=<?php echo $tweet['tweet_id']; ?>">Re</a>]
            </p>
            <p class="day">
              <a href="view.php?tweet_id=<?php echo $tweet['tweet_id']; ?>">
                <?php echo $tweet['created']; ?>
              </a>

              <?php if($_SESSION['id'] == $tweet['member_id']): ?>
                [<a href="edit.php?tweet_id=<?php echo $tweet['tweet_id']; ?>" style="color: #00994C;">編集</a>]
                [<a href="delete.php?tweet_id=<?php echo $tweet['tweet_id']; ?>" style="color: #F33;">削除</a>]
              <?php endif; ?>
              <?php if($tweet['reply_tweet_id'] > 0): ?>
                | <a href="view.php?tweet_id=<?php echo $tweet['reply_tweet_id']; ?>">返信元のつぶやき</a>
              <?php endif; ?>
            </p>
          </div>
        <?php endwhile; ?>
      </div>

    </div>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>







