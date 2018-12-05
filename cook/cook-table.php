<?php
include_once 'dbconnect.php';


//料理投稿機能に関するテーブル
$sql="CREATE TABLE cookdiary (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  username VARCHAR(255) default NULL ,
  title VARCHAR(255) default NULL ,
  comment VARCHAR(255) default NULL ,
  created datetime default null,
  picture LONGBLOB default NULL,
  extension VARCHAR(255) default null
);";
$stmt=$pdo->query($sql);


//費用計算グラフ機能に関するテーブル
$sql="CREATE TABLE cookcalculate (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  username VARCHAR(255) default NULL ,
  created datetime default null,
  cost INT(11) default null,
  eattimes INT(11) default null
);";
$stmt=$pdo->query($sql);


//ユーザー登録用
$sql="CREATE TABLE cookuser (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  username VARCHAR(255) default NULL ,
  password VARCHAR(255) default NULL
);";
$stmt=$pdo->query($sql);

//メッセージ機能用
$sql="CREATE TABLE cookreply (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  cookid INT(11) default NULL,
  username VARCHAR(255) default NULL ,
  comment VARCHAR(255) default NULL,
  created datetime default null,
  hyoujitime VARCHAR(255) default null
);";
$stmt=$pdo->query($sql);

//いいね機能用
$sql="CREATE TABLE cookgood (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  cookid INT(11) default NULL,
  username VARCHAR(255) default NULL
);";
$stmt=$pdo->query($sql);
?>
