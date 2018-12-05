<?php
require_once ("libs/jpgraph.php");
require_once ("libs/jpgraph_line.php");

$data=$_GET["data"];
// 文字列を配列に変換
$data = explode(",", $data);//配列をgetで送れなかったため


$graph = new Graph(400,400);
$graph->SetScale("textlin");

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");

$graph->title->Set("今までの節約記録");
$graph->title->SetFont(FF_MINCHO);

for($i=4;$i<=12;$i++){
  $labels[]=$i."月";
}
for($i=1;$i<=3;$i++){  //４月スタートにしたい
  $labels[]=$i."月";
}
$graph->xaxis->SetFont(FF_MINCHO);
$graph->xaxis->SetTickLabels($labels);

$lineplot = new LinePlot($data);

$graph->Add($lineplot);

$graph->Stroke();


?>
