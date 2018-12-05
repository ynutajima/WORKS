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
  </div>


<?php
include_once 'dbconnect.php';//DB接続

function goodDB($postid,$pdo,$cookid,$username){//----------------------------------いいね関数DB書き込み部分
  if(isset($postid)){
    //押されているか確認するために同ユーザー名があるか数える
    $sql="SELECT * FROM cookgood where cookid=:cookid and username=:username";
    $stmt=$pdo->prepare($sql);
    $stmt->bindvalue(':cookid',$cookid, PDO::PARAM_INT);
    $stmt->bindValue(':username',$username,PDO::PARAM_STR);
    $stmt->execute();
    $mycount=$stmt->fetchAll();
    $mycount=count($mycount);

    if($mycount==0){//まだ押されていない場合
      $sql=$pdo->prepare("INSERT INTO cookgood(cookid,username) VALUES(:cookid,:username)");
      $sql->bindvalue(':cookid',$cookid, PDO::PARAM_INT);
      $sql->bindvalue(':username',$username, PDO::PARAM_STR);
      $sql->execute();
    }else{//押されている
      $sql="delete from cookgood where cookid=:cookid and username=:username";//削除することで押してない状態に戻す
      $stmt=$pdo->prepare($sql);
      $stmt->bindvalue(':cookid',$cookid, PDO::PARAM_INT);
      $stmt->bindValue(':username',$username,PDO::PARAM_STR);
      $stmt->execute();
    }
  }
}//------------------------------------------------------------------いいね関数DB書き込み部分


//--------------------------------------------------------------いいね数のカウント関数
function goodcount($pdo,$cookid,$username){
  $sql="SELECT * FROM cookgood where cookid=:cookid";
  $stmt=$pdo->prepare($sql);
  $stmt->bindValue(':cookid',$cookid, PDO::PARAM_INT);
  $stmt->execute();
  $count=$stmt->fetchAll();
  $count=count($count);
  //自分が押していたら色を変えるために再度カウント自分がおしてるか↓
  //押されているか確認するために同ユーザー名があるか数える(自分が押してるか)↓
  $sql="SELECT * FROM cookgood where cookid=:cookid and username=:username";
  $stmt=$pdo->prepare($sql);
  $stmt->bindvalue(':cookid',$cookid, PDO::PARAM_INT);
  $stmt->bindValue(':username',$username,PDO::PARAM_STR);
  $stmt->execute();
  $mycount=$stmt->fetchAll();
  $mycount=count($mycount);
  if($mycount>0){
    echo "<font color=\"red\">$count</font>";
  }elseif($mycount==0){
  echo $count;
  }
}//--------------------------------------------------------------------------------------いいね数のカウント関数

$postid=$_POST["postid"];//hiddenで受け取る工夫
goodDB($_POST["{$postid}"],$pdo,$postid,$_SESSION["USERID"]);//いいねのDBへの書き込みを先に置くことでこのページでもいいねに対応

//いいね数をそれぞれの料理に対して数えランクにする
$sql ="select*from cookdiary";
$stmt = $pdo->query($sql);
$results=$stmt->fetchAll();//cookdiaryの料理個別のidを↓のwhereで使う
foreach($results as $row){
  $sql="SELECT * FROM cookgood where cookid=:cookid";
  $stmt=$pdo->prepare($sql);
  $stmt->bindValue(':cookid',$row["id"], PDO::PARAM_INT);
  $stmt->execute();
  $count=$stmt->fetchAll();
  $count=count($count);
  $rankarray[$row["id"]]=$count;//$countは被る可能性あるからkeyをidの方にしとく？配列に入れるpoint後で並べ替えたいから
}
arsort($rankarray);//$countの多い順に並べ替え

echo "<div class='main'>";
//いいねランキング順に表示機能↓
$i=1;
foreach($rankarray as $id=>$count){//$id→料理のid、$count→いいね数
  $sql ="select*from cookdiary where id=:id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':id',$id,PDO::PARAM_INT);
  $stmt->execute();
  $results=$stmt->fetchAll();
  foreach($results as $row){
    echo "<li>";
    if($i==1){
      echo "✨";
    }
    echo "【第{$i}位】";if($i==1){
      echo "✨";
    }echo "<br>";
    $i++;
    echo $row["username"]."<br>";
    echo $row["created"]."<br>";
    echo $row["title"]."<br>";
    echo wordwrap($row["comment"],50,"<br>",true)."<br>";
    echo '<img src="./cook-createimg.php?id='.$row["id"].'" width="100" height="100">';
    echo '<a href="cook-reply.php?id='.$row["id"].'">'."メッセージ"."</a>";
    //投稿ごとに区別、1投稿に個別のいいねボタン↓
    //ここではhiddenを使う工夫
    echo<<<form
    <form method="POST" action="cook-rank.php">
        <input name="postid" type="hidden" value="{$row["id"]}">
        <input name="{$row["id"]}" type="submit" value="いいね">
    </form>
form;
  //いいね機能、関数で実行
  goodcount($pdo,$row['id'],$_SESSION["USERID"]);
    echo "<br>";
    echo "<br>";
    echo "</li>";
  }
}
echo "</div>";

?>

</body>
</html>
