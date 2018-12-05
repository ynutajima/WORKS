<?php
// セッション開始
session_start();
// 既にログインしている場合にはメインページに遷移
if (isset($_SESSION["USERID"])) {
header('Location: cook-TOP.php');
exit;
}
$dsn='データベース名';
$user_db='ユーザー名';
$password_db='パスワード';
$error = '';

// ログインボタンが押されたら
if (isset($_POST['login'])) {
  if (empty($_POST['username'])) {
    $error = 'ユーザーIDが未入力です。';
  } else if (empty($_POST['password'])) {
    $error = 'パスワードが未入力です。';
  }
  if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $username = $_POST['username'];
    try {
      $pdo=new PDO($dsn,$user_db,$password_db,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
      $stmt = $pdo->prepare('SELECT * FROM cookuser WHERE username = ?');
      $stmt->execute(array($username));
      $password = $_POST['password'];
      $result = $stmt->fetch(PDO::FETCH_ASSOC);//$rowと$resultが同じってことか？今までの
      if ($password==$result['password']) {//password_verify($password, $result['password'])←ハッシュするならifの条件はこれ
        $_SESSION['USERID'] = $username;
        header('Location: cook-TOP.php');
        exit();
      } else {
        $error = 'ユーザーIDあるいはパスワードに誤りがあります。';
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ログイン</title>
</head>
<body>
<main>
<form id="loginForm" name="loginForm" action="" method="POST">
<p style="color:red;"><?php echo $error ?></p>
<br>
<label for="username">ユーザーID<br>
<input type="text" id="username" name="username" placeholder="ユーザーIDを入力" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
</label><br>
<label for="password">パスワード<br>
<input type="password" id="password" name="password" value="" placeholder="パスワードを入力">
</label>
<input type="submit" id="login" name="login" value="ログイン">
</form>
<p><a href="cook-newuser.php">新規登録はこちら</a></p>
</main>
</body>
</html>
