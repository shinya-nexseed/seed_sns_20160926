<?php
    // sprintf関数
    $str = sprintf('私は%sな%sです。', '初心者' ,'プログラマ');
    echo $str;

    $sql = sprintf('INSERT INTO `members` SET `nick_name`="%s",
                                             `email`="%s",
                                             `password`="%s"',
                                             "ネクシード",
                                             "nex@seed.net",
                                             "hogehoge"
                                             );
    echo $sql;
 ?>
