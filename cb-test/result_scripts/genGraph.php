<?php
include('wp-content/plugins/cb-test/result_scripts/jpgraph/jpgraph.php');
include('wp-content/plugins/cb-test/result_scripts/jpgraph/jpgraph_line.php');
include('wp-content/plugins/cb-test/result_scripts/jpgraph/jpgraph_pie.php');
include('wp-content/plugins/cb-test/result_scripts/jpgraph/jpgraph_pie3d.php');

function drawGraph1()
{
    $graph = new Graph(400, 300);

    $data = array(15, 85);

    // Create the Pie Graph.
    $graph = new PieGraph(350, 250);

    $theme_class = new VividTheme;
    $graph->SetTheme($theme_class);

    // Set A title for the plot
    $graph->title->Set("RAMAKRISHNA PRASAD");

    // Create
    $p1 = new PiePlot3D($data);
    $graph->Add($p1);

    $p1->ShowBorder();
    $p1->SetColor('black');
    $p1->ExplodeSlice(1);

    $graph->img->SetImgFormat("jpeg");

    //$graph->Stroke();

    if (file_exists("result2003.jpeg")) {
        unlink("result2003.jpeg");
    }

    $graph->Stroke("result2003.jpeg");

}
?>