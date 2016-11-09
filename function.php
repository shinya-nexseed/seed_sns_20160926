<?php
    // $debug = true;
    // 定数 define('定数名', 値);
    define('DEBUG', false);

    // echoの独自関数化
      // 1. UIに必要な表示をするため
      // 2. デバッグ用に変数の内容を表示するため
    function special_echo($val) {
        if (DEBUG) { // デバッグモードのときのみ処理される
            echo $val;
            echo '<br>'; // デフォルトで改行処理を含む
        }
    }

    special_echo('ほげ');
    special_echo('ふが');
    echo '普通のecho';

    // var_dumpの独自関数化
    function special_var_dump($val) {
        if (DEBUG) {
            echo '<pre>';
            var_dump($val);
            echo '</pre>';
        }
    }

    $ary = array('PHP', 'Ruby', 'C', 'Java', 'Python');
    special_var_dump($ary);
    var_dump($ary);





 ?>
