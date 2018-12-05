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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>cookrank</title>
<link rel="stylesheet" href="cook-rank.css">
</head>
<body>
  <div class="header">
      <div class="header-logo">
        COOK SNS
      </div>
      <div class="header-logout">
        <a href="cook-logout.php"><img src="https://icon-rainbow.com/i/icon_03850/icon_038500_256.png" width="40" height="40"  /><br>ログアウト</a>
      </div>
    <a class="header-logout" href="cook-rankTOP.php">戻る</a>
    <a class="header-logout" href="cook-TOP.php">トップへ戻る</a>
  </div>
  <div class="header-under">
  <div class='header-title'>獲得いいねランキング</div>
<?php
include_once 'dbconnect.php';//DB接続

$sql ="select*from cookuser";
$stmt = $pdo->query($sql);
$results=$stmt->fetchAll();//cookuserのデータ
foreach($results as $row){
  $sql="SELECT * FROM cookdiary where username=:username";//ユーザー毎にデータを見るそのユーザーが作った料理を取り出す
  $stmt=$pdo->prepare($sql);
  $stmt->bindValue(':username',$row["username"], PDO::PARAM_STR);
  $stmt->execute();
  $results=$stmt->fetchAll();//cookdiaryのデータ
  foreach($results as $row){
    $sql="SELECT * FROM cookgood where cookid=:cookid";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':cookid',$row["id"], PDO::PARAM_INT);
    $stmt->execute();
    $count=$stmt->fetchAll();
    $count=count($count);//それぞれの料理のいいねを数えている
    $countsum+=$count;
  }
  $countarray[$row["username"]]=$countsum;
  $countsum=0;//一つのユーザ終わるごとに0に戻す
}

$i=1;//順位表示用
arsort($countarray);
foreach($countarray as $username=>$count){
  echo "【第{$i}位】";
  echo $username."さん ";
  if($i==1){
    echo "✨";
  }
  echo "総いいね獲得数:{$count}";
  echo "<br>";
  echo "<br>";
  $i++;
}
?>

</body>
</html>
