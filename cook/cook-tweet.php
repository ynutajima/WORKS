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
<title>料理日記</title>
<link rel="stylesheet" href="cook-tweet.css">
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
<form name="form1" method="post" action="cook-tweet.php" enctype="multipart/form-data">
    <input type="text" name="title" value=""placeholder="料理名"><br>
    <textarea class="textarea" name="comment" value=""placeholder="コメント"></textarea><br>
      <input type="hidden" name="max_file_size" value="10000000">
      写真：
      <input type="file" name="upfile">
      <br>
    <input class="submit" type="submit" value="投稿"><br><br>
</form>
</div>

<?php
include_once 'dbconnect.php';

$title=$_POST["title"];
$comment=$_POST["comment"];
$date=date("Y/m/d/ H:i:s");

if (isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error']) && $_FILES["upfile"]["name"] !== ""){
            //エラーチェック
            switch ($_FILES['upfile']['error']) {
                case UPLOAD_ERR_OK: // OK
                    break;
                case UPLOAD_ERR_NO_FILE:   // 未選択
                    throw new RuntimeException('ファイルが選択されていません', 400);
                case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
                    throw new RuntimeException('ファイルサイズが大きすぎます', 400);
                default:
                    throw new RuntimeException('その他のエラーが発生しました', 500);
            }

            //画像・動画をバイナリデータにする．
            $raw_data = file_get_contents($_FILES['upfile']['tmp_name']);

            //拡張子を統一する
            $tmp = pathinfo($_FILES["upfile"]["name"]);
            $extension = $tmp["extension"];
            if($extension === "jpg" || $extension === "jpeg" || $extension === "JPG" || $extension === "JPEG"){
                $extension = "jpeg";
            }
            elseif($extension === "png" || $extension === "PNG"){
                $extension = "png";
            }
            elseif($extension === "gif" || $extension === "GIF"){
                $extension = "gif";
            }
            elseif($extension === "mp4" || $extension === "MP4"){
                $extension = "mp4";
            }
            else{
                echo "非対応ファイルです．<br/>";
                echo ("<a href=\"cookdairy.php\">戻る</a><br/>");
                exit(1);
            }

            if(!empty($title) && !empty($comment) && !empty($_FILES["upfile"]["name"])){
              //画像・動画をDBに格納．
              $sql = "INSERT INTO cookdiary(username,title,comment,created,picture,extension) VALUES (:username,:title, :comment, :created, :picture, :extension)";
              $stmt = $pdo->prepare($sql);
              $stmt -> bindvalue(":username",$_SESSION["USERID"], PDO::PARAM_STR);//MYpage用
              $stmt -> bindvalue(":title",$title, PDO::PARAM_STR);
              $stmt -> bindvalue(":comment",$comment, PDO::PARAM_STR);
              $stmt -> bindparam(":created",$date, PDO::PARAM_STR);
              $stmt -> bindvalue(":picture",$raw_data, PDO::PARAM_LOB);
              $stmt -> bindValue(":extension",$extension, PDO::PARAM_STR);
              $date=date("Y/m/d/ H:i:s");
              $stmt -> execute();
            }
            else{
              echo "未入力箇所があります";
            }


}//画像、コメント投稿機能、インスタ的な感じ

?>

<br><br>
<a href="cook-TOP.php">トップへ戻る</a>

</body>
</html>
