<?php
// DB接続設定
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password
    , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//データベース内にテーブルを作成
$sql = "CREATE TABLE IF NOT EXISTS mission_5"
	." ("
	. "num INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date DATETIME"
	.");";
$stmt = $pdo->query($sql);
//変数を書き出す
$name = $_POST["name"];
$comment = $_POST["comment"];
$date = date("Y/m/d H:i:s");
$mark = $_POST["mark"];
$delnum = $_POST["delete_number"];
$edit_num = $_POST["edit_number"];
$password1 = $_POST["password1"];
$password2 = $_POST["password2"];
$password3 = $_POST["password3"];
//編集番号があった場合(変数を作る)
if(!empty($edit_num) && $password3 == "password"){
    //編集番号と同じ投稿番号の投稿の名前・コメントの変数を作る
    $sql = 'SELECT * FROM mission_5 WHERE num=:num';
    $stmt = $pdo->prepare($sql);//SQLを準備
    $stmt->bindParam(':num', $edit_num, PDO::PARAM_INT);//差し替えるパラメータの値を指定
    $stmt->execute();//SQLを実行
    $results = $stmt->fetchAll(); 
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		$a1 = $row['name'];
		$a2 = $row['comment'];
	}
}
//1つ目のphpは変数を作ったら終了
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-2</title>
</head>
<body>
<form action="mission_5-2.php" method="post">
    <input type="text" name="name" placeholder="お名前"
        value="<?php echo $a1; ?>"><br>
    <input type="text" name="comment" placeholder="コメント"
        value="<?php echo $a2; ?>">
    <input type="hidden" name="mark" placeholder="目印"
        value="<?php echo $edit_num; ?>"><br>
    <input type="text" name="password1" placeholder="パスワード">
    <input type="submit" name="submit" value="送信"><br><br>
    <input type="text" name="delete_number" placeholder="削除対象番号"><br>
    <input type="text" name="password2" placeholder="パスワード">
    <input type="submit" name="delete" value="削除"><br><br>
    <input type="text" name="edit_number" placeholder="編集対象番号"><br>
    <input type="text" name="password3" placeholder="パスワード">
    <input type="submit" name="edit" value="編集">
</form>
</body>
</html>
<?php
//パスワード正誤判定表示
//パスワードが入力されてない場合
if(empty($password1) && empty($password2) && empty($password3)){
//表示なし
//パスワードが合ってる場合
}elseif($password1 == "password" || $password2 == "password" 
    || $password3 == "password"){
//表示なし
//パスワードが違う場合の表示
}elseif($password1 != "password" || $password2 != "password" 
    || $password3 != "password"){
    echo "パスワードが違います。<br>";
}
// DB接続設定
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password
    , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//投稿機能
if(!empty($name) && !empty($comment) && $password1 == "password"){
    //目印がない時
    if(empty($mark)){
        //投稿内容をデータベースに書き込む
        $sql = $pdo -> prepare("INSERT INTO mission_5
        (name, comment,date) VALUES (:name, :comment, :date)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> execute();
    //目印がある時
    }elseif(!empty($mark)){
        //目印と同じ投稿番号の投稿を編集する
        $num = $mark; //変更する投稿番号
        $sql = 'UPDATE mission_5 SET name=:name,comment=:comment
        ,date=:date WHERE num=:num';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':num', $num, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
        //目印を消す
        $_POST["mark"] = "";
    }
    
//削除機能
}elseif(!empty($delnum) && $password2 == "password"){
    //削除番号と同じ投稿番号の投稿を削除する
    $num = $delnum;
    $sql = 'delete from mission_5 WHERE num=:num';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    $stmt->execute();
}

//表示機能
//入力したデータレコードを抽出し、表示する
$sql = 'SELECT * FROM mission_5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['num'].' ';
	echo $row['name'].' ';
	echo $row['comment'].' ';
	echo $row['date'].'<br>';
	echo "<hr>";
}
?>