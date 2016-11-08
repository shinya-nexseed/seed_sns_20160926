<?php



    // 関数の実行
    hello();

    // 関数の定義
    function hello() {
        echo 'hello';
        echo '<br>';
    }

    // 関数の実行
    hello();

    // 引数
    function aisatsu($name){
      // $name = '太郎';
      echo '初めまして、' . $name . 'さん！';
      echo 'お元気ですか？';
    }
    // $name ← 関数の外で引数を使うことはできません

    aisatsu('太郎');
    aisatsu('花子');


    // 複数の引数
    // 2つの値の合計値を計算する関数
    // function plus($num1, $num2) {
    //   $result = $num1 + $num2;
    //   echo '合計は' . $result . 'です。';
    // }


    // // 関数の呼び出し
    // plus(10, 5);

    // 2つの値の合算値を出す関数
    function plus($num1, $num2) {
      $result = $num1 + $num2;
      return $result;
    }

    // 関数の戻り値をそのまま出力する場合
    echo '加算の結果は' . plus(30, 20) . 'です';

    // 変数「$sum」に戻り値を格納して出力する場合
    $sum = plus(30, 20);
    echo '合計は' . $sum . 'です';





    // 四則演算関数
    // function calc($num1, $num2, $s) {
    //     if ($s == '+') {
    //       # code...
    //     } elseif($s == '-') {
    //     }
    // }
    // calc(10, 5, '-');


    // 練習問題
    function nexseed($greeting, $name) {
        // $greeting = 'おはようございます';
        $result = $greeting . '、' . $name . 'さん';
        return $result
    }

    $result = nexseed('おはようございます', 'のび太');
    echo $result;




 ?>
