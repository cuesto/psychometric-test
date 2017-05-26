<?php
require_once 'phplot.php';
function drawBar3($val1,$val2,$val3,$val4,$img)
{
$data = array(
  array('Adjustment capacity', $val1),   array('Discipline', $val2),
  array('Time management',  $val3),   array('Stress management', $val4),
);

$plot = new PHPlot(349,159);
$plot->SetImageBorderType('plain'); // Improves presentation in the manual
$plot->SetTitle("");
$plot->SetBackgroundColor('white');
// $plot->SetLegend(array('<30:Needs work', '30-60:Good', '>60:Right Track'));
 //$plot->SetLegendPosition(0,0,'image','image','image');
#  Set a tiled background image:
//$plot->SetPlotAreaBgImage('drop.png', 'centeredtile');
#  Force the X axis range to start at 0:
$plot->SetPlotAreaWorld();
#  No ticks along Y axis, just bar labels:
$plot->SetYTickPos('none');
#  No ticks along X axis:
$plot->SetXTickPos('none');
#  No X axis labels. The data values labels are sufficient.
$plot->SetXTickLabelPos('none');
#  Turn on the data value labels:
$plot->SetXDataLabelPos('plotin');
#  No grid lines are needed:
$plot->SetDrawXGrid(FALSE);
#  Set the bar fill color:
$plot->SetDataColors('orange');
#  Use less 3D shading on the bars:
$plot->SetShading(2);
 $plot->SetFontTTF('x_title', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
  $plot->SetFontTTF('title', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
    $plot->SetFontTTF('generic', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
      $plot->SetFontTTF('legend', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
        $plot->SetFontTTF('x_label', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
          $plot->SetFontTTF('y_label', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
            $plot->SetFontTTF('y_title', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
$plot->SetDataValues($data);
$plot->SetDataType('text-data-yx');
$plot->SetPlotType('bars');

  $plot->SetIsInline(true);
  $plot->SetOutputFile("wp-content/plugins/cb-test/result_scripts/tmp/".$img);
  
$plot->DrawGraph();
}


function drawBar33($val1,$val2,$val3,$val4,$img)
{
    
    include("wp-content/plugins/cb-test/result_scripts/libchart/libchart/classes/libchart.php");
    
    $chart = new HorizontalBarChart(349, 159);

	$dataSet = new XYDataSet();
	$dataSet->addPoint(new Point("Adjustment capacity", $val1));
	$dataSet->addPoint(new Point("Discipline", $val2));
	$dataSet->addPoint(new Point("Time management", $val3));
    $dataSet->addPoint(new Point("Stress management", $val4));
	$chart->setDataSet($dataSet);

	$chart->setTitle("");
	$chart->render("wp-content/plugins/cb-test/result_scripts/tmp/$img");
}
?>