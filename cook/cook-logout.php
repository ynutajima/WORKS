<?php
session_start();
// セッションクリア
session_destroy();
$error = "ログアウトしました。";
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>ログアウト</title>
</head>
<body>
<div><?php echo $error; ?></div>
<ul>
<li><a href="cook-login.php">ログインページへ</a></li>
</ul>
</body>
</html>
