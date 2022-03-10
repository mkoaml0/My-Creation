<?php
      
      $dsn = 'ʼデータベース名';
       
      $user = 'ユーザー名';
      
      $password = 'パスワード';
      
      //データベースに接続する
      
      $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
      
     
     //もしまだテーブル名"mi5"が存在しないならテーブル"mi5"を作成する
      $sql = "CREATE TABLE IF NOT EXISTS mi5"
      ." ("
     //自動で登録されているナンバリング
      . "id INT AUTO_INCREMENT PRIMARY KEY,"
     //名前を入れる。32文字まで
      . "name char(32),"
     //コメントを入れる。80文字まで
      . "comment TEXT,"
     //日時を入れる
      . "date DATETIME,"
     //パスワードを入れる
      . "pass char(32)"
      .");";
      $stmt = $pdo->query($sql);
     
//編集番号が送信されたなら編集(更新UPDATE)
     if(!empty($_POST["editnum"])){
     if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){
         //指定した編集番号を代入
         $id = $_POST["editnum"];
         $name = $_POST["name"];
         $comment = $_POST["comment"];
         $date = date("Y/m/d/ H:i:s");
         $pass = $_POST["pass"];
         
         $sql_u  = 'UPDATE mi5 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
         $stmt_u = $pdo->prepare($sql_u);
         $stmt_u -> bindParam(':id', $id, PDO::PARAM_INT);
         $stmt_u -> bindParam(':name', $name, PDO::PARAM_STR);
         $stmt_u -> bindParam(':comment', $comment, PDO::PARAM_STR);
         $stmt_u -> bindParam(':date', $date, PDO::PARAM_STR);
         $stmt_u -> bindParam(':pass', $pass, PDO::PARAM_STR);
            //SQL実行  
            $stmt_u->execute();
        }
    }
         
    //編集番号が送信されてないなら追記=新規投稿(作成INSERT)
    elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){
       $name = $_POST["name"];
       $comment = $_POST["comment"];
       $date = date("Y/m/d/ H:i:s");
       $pass = $_POST["pass"];
     
       $sql_w = $pdo->prepare("INSERT INTO mi5 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
       //登録するデータをセット
       $sql_w -> bindParam (':name', $name, PDO::PARAM_STR);
       $sql_w -> bindParam (':comment', $comment, PDO::PARAM_STR);
       $sql_w -> bindParam (':date', $date, PDO::PARAM_STR);
       $sql_w -> bindParam (':pass', $pass, PDO::PARAM_STR);
       //SQL実行
       $sql_w -> execute ();
    }
 
    //削除機能(DELETE)
   elseif(!empty($_POST['delete']) && !empty($_POST['delpass'])) {
       $delpass = $_POST['delpass'];
       $delete = $_POST["delete"];
     
       $sql_d = 'DELETE FROM mis5 WHERE id=:delid and pass=:delpass';
       $stmt_d = $pdo->prepare($sql_d);
       //登録するデータをセット
       $stmt_d->bindParam(':delid', $delete, PDO::PARAM_INT);
       $stmt_d->bindParam(':delpass', $delpass, PDO::PARAM_STR);
       //SQL実行
       $stmt_d->execute();
   }
  
   //編集機能
   elseif(!empty($_POST['edit']) && !empty($_POST['e_pass'])){
       $edit = $_POST["edit"];
       $e_pass = $_POST["e_pass"];
     
       $sql_e = 'SELECT * FROM mi5 WHERE id=:e_id and pass=:e_pass';
       $stmt_e = $pdo->prepare($sql_e);
       //登録するデータをセット
       $stmt_e -> bindParam (':e_id', $edit, PDO::PARAM_INT);
       $stmt_e -> bindParam (':e_pass', $e_pass, PDO::PARAM_STR);
       //SQL実行
       $stmt_e->execute();
        
       $results_e = $stmt_e->fetchAll();
       foreach ($results_e as $e_row){
           $e_name = $e_row['name'];
           $e_comment = $e_row['comment'];
           $e_pass = $e_row['pass'];
       }
    }
 ?>
 
 <!DOCTYPE html>
 <html> 
 <head> 
 <meta charset="utf-8" /> 
 </head>
 <body> 

   <form action="" method="post">
             <input type="text" name="name" placeholder="名前" 
             value="<?php if(!empty($e_name)){
                             echo $e_name;
                             }              
                             else{
                             }?>"><br>
             <input type="text" name="comment" placeholder="コメント" 
             value="<?php if(!empty($e_comment)){
                             echo $e_comment;
                             }
                             else{
                             } ?>"><br>
             <input type="text" name="pass" placeholder="パスワード" 
             value="<?php if(!empty($e_pass)){
                             echo $e_pass;
                                 }
                             else{
                                 }?>">
             <input type="submit" name="submit">
             <!-- 編集対象番号を非表示 -->
             <input type="hidden" name="editnum" placeholder="編集対象番号" 
                 value="<?php if(!empty($_POST["edit"])){//編集番号が空でなければ編集番号を表示
                                 echo $_POST["edit"];
                                 }
                                 else{
                                 }?>">
             <br><br>
             <input type="text" name="delete" placeholder="削除対象番号"><br>
             <input type="text" name="delpass" placeholder="パスワード">
             <input type="submit" name="submit" value="削除">
             <br><br>
             <input type="text" name="edit" placeholder="編集対象番号"><br>
             <input type="text" name="e_pass" placeholder="パスワード">
             <input type="submit" name="submit" value="編集">
             
         </form>
         </body>
         </html>
         <?php
            //入力したデータレコードを抽出し、表示する
            $sql = 'SELECT * FROM mi5';
            $stmt = $pdo->query($sql);
            $results = $stmt ->fetchAll();
            foreach ($results as $row){
             echo $row['id'].' ';
             echo $row['name'].' ';
             echo $row['comment'].' ';
             echo $row['date'].'<br>';
             echo '<hr>';
           }
           //データベースの接続解除
            $pdo = null;
         ?>