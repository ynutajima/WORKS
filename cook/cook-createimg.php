<?php
include_once 'dbconnect.php';

$id = $_GET['id'];

$extensionTypes = array(
        'png' => 'image/png',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'mp4' => 'video/mp4'
    );

$sql ="select*from cookdiary where id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id',$id,PDO::PARAM_INT);
$stmt->execute();
$results=$stmt->fetchAll();
foreach($results as $row){
        header('Content-Type: $extensionTypes[$row["extension"]]');
        echo ($row['picture']);
}

?>
