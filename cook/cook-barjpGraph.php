<?php
require_once ("libs/jpgraph.php");
require_once ("libs/jpgraph_bar.php");



$costsum = $_GET['costsum'];
$gaisyokusum = $_GET['gaisyokusum'];

$databary=array($costsum,$gaisyokusum);

// New graph with a drop shadow
$graph = new Graph(400,400);
$graph->SetShadow();

// Use a "text" X-scale
$graph->SetScale("textlin",0,50000);

// Set title and subtitle
$graph->title->Set("今月の節約記録");

// Use built in font
$graph->title->SetFont(FF_MINCHO);

$labels = array("自炊", "節約");
$graph->xaxis->SetFont(FF_MINCHO);
$graph->xaxis->SetTickLabels($labels);


// Create the bar plot
$b1 = new BarPlot($databary);

// The order the plots are added determines who's ontop
$graph->Add($b1);

// Finally output the  image
$graph->Stroke();

?>
