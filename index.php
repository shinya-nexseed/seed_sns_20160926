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
 ?>

<?php echo $member['nick_name']; ?><br>
<img src="member_picture/<?php echo $member['picture_path']; ?>" alt="" width="100">









