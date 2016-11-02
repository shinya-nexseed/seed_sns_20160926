<?php
    // DB接続処理をまとめたファイル
    $db = mysqli_connect('localhost', 'root', 'mysql', 'seed_sns') or die(mysqli_connect_error());
    mysqli_set_charset($db, 'utf8');

    // PDOというデータベース操作プログラムを使用していた
    // mysqli関数を使用して操作します

    // PDOのメリット
    // DBの種類に関係なく使用できる
    // オブジェクト指向で書ける

    // mysqli関数のメリット
    // プログラムが若干読みやすい
    // 初学者向け
    // ※ 基本的には上級者はPDOなどのオブジェクト指向を好みます
 ?>
