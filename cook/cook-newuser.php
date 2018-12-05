<?php
// セッション開始
session_start();
// 既にログインしている場合にはメインページに遷移
if (isset($_SESSION['USERID'])) {
  header('Location: cook-TOP.php');
  exit;
}

$dsn='データベース名';
$user_db='ユーザー名';
$password_db='パスワード';
$error = '';

// ログインボタンが押されたら
if (isset($_POST['signUp'])) {
  if (empty($_POST['username'])) {
    $error = 'ユーザーIDが未入力です。';
  }else if (empty($_POST['password'])) {
    $error = 'パスワードが未入力です。';
  }
  if (!empty($_POST['username']) && !empty($_POST['password'])) {//両方入力されているとき
    $username = $_POST['username'];
    $password = $_POST['password'];
    $pdo=new PDO($dsn,$user_db,$password_db,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

    try{
      $sqlname = "SELECT COUNT(*) FROM cookuser WHERE `username` = '$username'";//$usernameという行が存在するかのチェック
      $ss = $pdo->query($sqlname);
      $username_cheak = $ss->fetchColumn();
      $pass_count = strlen($_POST['password']);
      // idの重複とパスワードの桁数チェック
      if($usename_cheak > 0){
        throw new Exception('そのユーザーIDは既に使用されています。');
      }
      if ($pass_count < 6) {
        throw new Exception('パスワードは6桁以上で入力してください。');
      }

      $stmt = $pdo->prepare('INSERT INTO cookuser(username, password) VALUES (:username, :password)');
      //$pass = password_hash($password, PASSWORD_DEFAULT); //対応しているサーバーならこっちでも大丈夫ハッシュ化可能
      $stmt->bindValue(':username', $username, PDO::PARAM_STR);
      $stmt->bindValue(':password', $password, PDO::PARAM_STR);//hashなら$pass
      $stmt->execute();
      $_SESSION['USERID'] = $username;
      echo '<script>
      alert("登録が完了しました。");
      location.href="cook-TOP.php";
      </script>';
    } catch(Exception $e){
      $error = $e->getMessage();
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>新規登録</title>
</head>
<body>
<main>
<form id="loginForm" name="loginForm" action="" method="POST">
<p style="color:red;"><?php echo $error ?></p>
<label for="username">ユーザーID<br>
<input type="text" id="username" name="username" placeholder="ユーザー名を入力" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
</label><br>
<label for="password">パスワード<br>
<input type="password" id="password" name="password" value="" placeholder="パスワードを入力">※6桁以上
</label>
<input type="submit" id="signUp" name="signUp" value="新規登録" class="btn up">
</form>
</main>
</body>
</html>
