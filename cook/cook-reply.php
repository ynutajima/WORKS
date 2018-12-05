<?php
session_start();
// ログイン状態チェック
if (!isset($_SESSION["USERID"])) {
header("Location: cook-login.php");
exit;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta charset="UTF-8">
<title>メッセージ</title>
<link rel="stylesheet" href="cook-reply.css">
</head>
<body>


<?php
include_once 'dbconnect.php';
$cookid=$_GET["id"];//表示中の写真のid
if(empty($cookid)){
  $cookid=$_POST["cookid"];
}
$comment=$_POST["comment"];
$date=date("Y/m/d/ h:i:s");
$hyoujitime=date("m/d H:i");
?>

<p class="picture">
<?php
//メッセージ対象の写真を表示
$sql ="select*from cookdiary where id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id',$cookid,PDO::PARAM_INT);
$stmt->execute();
$results=$stmt->fetchAll();
foreach($results as $row){
  echo $row["username"]."<br>";
  echo $row["created"]."<br>";
  echo $row["title"]."<br>";
  echo $row["comment"]."<br>";
  echo '<img src="./cook-createimg.php?id='.$row["id"].'" width="100" height="100">';
}
?>
<br>
<br>
</p>
<p>
<?php
echo<<<form
<form method="POST" action="cook-reply.php">
    <textarea class="textarea" name="comment" value=""placeholder="メッセージ"></textarea>
    <input class="submit" type="submit" value="送信"><br>
    <input type="hidden" name="cookid" value=$cookid>
</form>
form;
?>


<?php
//メッセージを送信DB保存
if(!empty($comment)){
  $sql=$pdo->prepare("INSERT INTO cookreply(cookid,username,comment,created,hyoujitime) VALUES(:cookid,:username,:comment,:created,:hyoujitime)");
  $sql->bindValue(':cookid',$cookid,PDO::PARAM_INT);
  $sql->bindvalue(':username',$_SESSION["USERID"], PDO::PARAM_STR);
  $sql->bindValue(':comment',$comment,PDO::PARAM_STR);
  $sql->bindValue(':created',$date,PDO::PARAM_STR);
  $sql->bindValue(':hyoujitime',$hyoujitime,PDO::PARAM_STR);
  $sql->execute();
}

//メッセージ表示
$sql="select*from cookreply where cookid=:cookid order by id desc";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':cookid',$cookid,PDO::PARAM_INT);
$stmt->execute();
$results=$stmt->fetchAll();
foreach($results as $row){
  echo "From：".$row['username']."さん"."<br>";
  echo $row["comment"]." ";
  echo $row["hyoujitime"]." ";
  echo "<br><br>";
}
?>
<br><br>
<a href="javascript:history.back()">戻る</a>
<a href="cook-TOP.php">トップへ戻る</a>
</p>
</body>
</html>
