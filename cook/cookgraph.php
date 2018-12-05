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
<title>節約グラフ</title>
<link rel="stylesheet" href="cookgraph.css">
</head>
<body>
  <div class="header">
      <div class="header-logo">
        COOK SNS
      </div>
      <div class="header-logout">
        <a href="cook-logout.php"><img src="https://icon-rainbow.com/i/icon_03850/icon_038500_256.png" width="40" height="40"  /><br>ログアウト</a>
      </div>
    <a class="header-logout" href="cook-TOP.php">トップへ戻る</a>
  </div>
  <form method="POST" action="cookgraph.php">
      <input type="text" name="cost" value=""placeholder="食材費用"><br>
      <input type="text" name="eattimes" value=""placeholder="〇食分">※半角数字で入力して下さい<br>
      <input type="submit" value="送信"><br><br>
  </form>


<?php
include_once 'dbconnect.php';

$cost=$_POST["cost"];
$eattimes=$_POST["eattimes"];
$date=date("Y/m/d/ h:i:s");
$dateY=date("Y");
$dateYm=date("Y-m");


//入力された値をテーブルに書き込む
if(!empty($cost) and !empty($eattimes)){ //入力フォーム両方に値が入力された場合
  $sql = "INSERT INTO cookcalculate(username,created,cost,eattimes) VALUES (:username,:created,:cost,:eattimes)";
  $stmt = $pdo->prepare($sql);
  $stmt -> bindvalue(":username",$_SESSION["USERID"], PDO::PARAM_STR);
  $stmt -> bindvalue(":created",$date,PDO::PARAM_STR);
  $stmt -> bindValue(":cost",$cost,PDO::PARAM_INT);
  $stmt -> bindValue(":eattimes",$eattimes,PDO::PARAM_INT);
  $stmt -> execute();
}elseif(!empty($cost) and empty($eattimes)){
  echo "データを正しく入力してください！<br>";
}elseif(empty($cost) and !empty($eattimes)){
  echo "データを正しく入力してください！<br>";
}



//select部分//棒グラフ用
$sql="select*from cookcalculate where username=:username and created LIKE \"{$dateYm}%\"";//〇月に当てはまるデータのみ取得、(今はリアルタイムの月の物しか見れない)
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username',$_SESSION["USERID"],PDO::PARAM_STR);
$stmt -> execute();
$results=$stmt->fetchAll();
foreach($results as $row){
  $costsum=$costsum+$row["cost"];
  $eattimessum=$eattimessum+$row["eattimes"];
}
$gaisyoku=1000;
$gaisyokusum=$gaisyoku*$eattimessum;
$setuyaku=$gaisyokusum - $costsum;
echo "<ul>";
echo "<li>";
echo '<img src="./cook-barjpGraph.php?costsum='.$costsum.'&gaisyokusum='.$gaisyokusum.'" width="500" height="500">';
echo "<br>";
echo "今月の節約したお金：\\";
echo $setuyaku;
echo "</li>";



//select//折れ線グラフ用
for($i=4;$i<=12;$i++){
  $eattimessum=0;
  $costsum=0;//繰り返しで引き継がれないように０に戻す
  $ii=sprintf("%02d", $i);
  $search=$dateY."-".$ii."%";
  $sql="select*from cookcalculate where username=:username and created LIKE \"$search\"";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':username',$_SESSION["USERID"],PDO::PARAM_STR);
  $stmt -> execute();
  $results=$stmt->fetchAll();
  foreach($results as $row){
    $costsum=$costsum+$row["cost"];
    $eattimessum=$eattimessum+$row["eattimes"];
  }
  $gaisyoku=1000;
  $gaisyokusum=$gaisyoku*$eattimessum;
  $setuyaku=$gaisyokusum - $costsum;
  $data[]=$setuyaku;
}
for($i=1;$i<=3;$i++){//4月スタートのため
  $eattimessum=0;
  $costsum=0;//繰り返しで引き継がれないように０に戻す
  $ii=sprintf("%02d", $i);
  $search=$dateY."-".$ii."%";
  $sql="select*from cookcalculate where username=:username and created LIKE \"$search\"";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':username',$_SESSION["USERID"],PDO::PARAM_STR);
  $stmt -> execute();
  $results=$stmt->fetchAll();
  foreach($results as $row){
    $costsum=$costsum+$row["cost"];
    $eattimessum=$eattimessum+$row["eattimes"];
  }
  $gaisyoku=1000;
  $gaisyokusum=$gaisyoku*$eattimessum;
  $setuyaku=$gaisyokusum - $costsum;
  $data[]=$setuyaku;
}
//今までの節約合計を数える↓
$setuyakusum=0;
foreach($data as $setuyaku){
  $setuyakusum=$setuyakusum+$setuyaku;
}
// 配列を文字列に変換
$data=implode(",", $data); // 区切り文字は","
echo "<li>";
echo '<img src="./cook-linejpGraph.php?data='.$data.'" width="500" height="500">';
echo "<br>";
echo "今までの節約したお金：\\".$setuyakusum;
echo "</li>";
echo "</ul>"
?>


</body>
</html>
