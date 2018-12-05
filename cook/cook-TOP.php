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
<title>CookDiary</title>
<link rel="stylesheet" href="cook-TOP.css">
</head>
<body>

  <div class="header">
    <div class="header-logo">
      COOK SNS
    </div>
    <div class="header-logout">
      <a href="cook-logout.php"><img src="https://icon-rainbow.com/i/icon_03850/icon_038500_256.png" width="40" height="40"  /><br>ログアウト</a>
    </div>
  </div>

<div class="main">
<br><br>
  <ul>
    <li>
      <a href="cook-tweet.php"><img src="https://icon-rainbow.com/i/icon_02453/icon_024530_256.png" width="100" height="100"  /><br>料理を記録する</a>
    </li>
    <li>
      <a href="cook-myshow.php"><img src="https://バス停.yokohama/info/flow/images/mypage.png" width="100" height="100"  /><br>作った料理を見る</a>
    </li>
    <li>
      <a href="cookgraph.php"><img src="http://icooon-mono.com/i/icon_10498/icon_104980.svg" width="100" height="100"  /><br>自炊で節約記録</a>
    </li>
    <li>
      <a href="cook-everyshow.php"><img src="https://seegrid.com/wp-content/uploads/2016/09/team_icon_3.png" width="100" height="100"  /><br>みんなの料理</a>
    </li>
    <li>
      <a href="cook-rankTOP.php"><img src="https://icon-rainbow.com/i/icon_02067/icon_020670_256.png" width="100" height="100"  /><br>ランキング</a>
    </li>
  </ul>
</div>


</body>
</html>
