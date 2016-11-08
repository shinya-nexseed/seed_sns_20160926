<?php
    session_start();
    require('dbconnect.php');

    // ログインしているか
    if (isset($_SESSION['id'])) {
        // 指定されたつぶやきが、ログインしているユーザーのものかチェック
        $tweet_id = $_REQUEST['tweet_id'];

        $sql = sprintf('SELECT * FROM `tweets` WHERE `tweet_id`=%d',
                          mysqli_real_escape_string($db, $tweet_id)
                       );
        $record = mysqli_query($db, $sql) or die(mysqli_error($db));
        $table = mysqli_fetch_assoc($record);

        // 一致すれば削除処理
        if ($table['member_id'] == $_SESSION['id']) {
            $sql = sprintf('DELETE FROM `tweets` WHERE `tweet_id`=%d',
                              mysqli_real_escape_string($db, $tweet_id)
                           );
            mysqli_query($db, $sql) or die(mysqli_error($db));
        }
    }

    header('Location: index.php');
    exit();
