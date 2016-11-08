<?php
    // $debug = true;
    // 定数 define('定数名', 値);
    define('DEBUG', false);

    // echoの独自関数化
      // 1. UIに必要な表示をするため
      // 2. デバッグ用に変数の内容を表示するため
    function special_echo($val) {
        if (DEBUG) {
            echo $val;
            echo '<br>';
        }
    }

    special_echo('ほげ');
    special_echo('ふが');
    echo '普通のecho';

    // var_dumpの独自関数化
 ?>
