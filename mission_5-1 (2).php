<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>
            Mission5-1
        </title>
    </head>
    <body>

        <?php
        
        //データベースアクセス
        $dsn='mysql:dbname=tb230755db;host=localhost';
        $user='tb-230755';
        $password='H78Zu27QdB';
        $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //データベースアクセス終了

        //データベース構築
        $sql='CREATE TABLE IF NOT EXISTS my掲示板'
        . "("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT"
        . ");";
        $stmt=$pdo -> query($sql);
        //データベース構築終了

        //変数定義
        $name_input=filter_input(INPUT_POST, 'name');
        $comment_input=filter_input(INPUT_POST, 'comment');
        $delete_input=filter_input(INPUT_POST, 'delete');
        $edit_input=filter_input(INPUT_POST, 'edit');
        $edit_number_input=filter_input(INPUT_POST, 'edit_number');
        //変数定義終了

        //新規投稿
        if(!empty($name_input && $comment_input) && empty($edit_number_input))
        {
            $sql=$pdo -> prepare("INSERT INTO my掲示板 (name, comment) VALUES (:name, :comment)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $name=$name_input;
            $comment=$comment_input;
            $sql -> execute();
        }
        //新規投稿終了

        //投稿削除
        if(!empty($delete_input))
        {
            $id=$delete_input;
            $sql='delete from my掲示板 where id=:id';
            $stmt=$pdo -> prepare($sql);
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();
        }
        //投稿削除終了

        //編集内容
        $newnumber='';
        $newname='';
        $newcomment='';
        if(!empty($edit_input))
        {
            $sql='SELECT * FROM my掲示板';
            $stmt=$pdo -> query($sql);
            $results=$stmt -> fetchAll();
            foreach($results as $row)
            {
                if($row['id']==$edit_input)
                {
                    $newnumber=$row['id'];
                    $newname=$row['name'];
                    $newcomment=$row['comment'];
                }
            }
        }
        //編集内容終了

        //投稿編集
        if(!empty($name_input && $comment_input && $edit_number_input))
        {
            $id=$edit_number_input;
            $name=$name_input;
            $comment=$comment_input;
            $sql='UPDATE my掲示板 SET name=:name, comment=:comment WHERE id=:id';
            $stmt=$pdo -> prepare($sql);
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        //投稿編集終了

        ?>


        <form action="" method="post">
            <input type="text" name="name" placeholder="名前"
             value="<?=$newname?>"><br>
            <input type="text" name="comment" placeholder="コメント"
             value="<?=$newcomment?>">
            <input type="hidden" name="edit_number"
             value="<?=$newnumber?>"><br>
            <input type="submit" value="投稿"><br><br>
            <input type="number" name="delete" placeholder="削除番号"><br>
            <input type="submit" value="削除"><br><br>
            <input type="number" name="edit" placeholder="編集番号"><br>
            <input type="submit" value="編集"><br>
        </form>

        <?php

        //表示
        $sql='SELECT * FROM my掲示板';
        $stmt=$pdo -> query($sql);
        $results=$stmt -> fetchAll();
        foreach($results as $row)
        {
            echo $row['id']. ' [';
            echo $row['name']. '] ';
            echo $row['comment']. '<br>';
        }
        //表示終了

        ?>

    </body>
</html>