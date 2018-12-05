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
<link rel="stylesheet" href="cook-rankTOP.css">
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
<div class="header-under">
<div class='header-title'>獲得いいねランキング</div>
</div>
  <li>
    <a href="cook-rank.php"><img src="https://icon-rainbow.com/i/icon_04268/icon_042680_256.png" width="100" height="100"  /><br>人気の料理を見る</a>
  </li>
  <li>
    <a href="cook-rankuser.php"><img src="http://icooon-mono.com/i/icon_11671/icon_116710_256.png" width="100" height="100"  /><br>人気のユーザーを見る</a>
  </li>
</body>
</html>
