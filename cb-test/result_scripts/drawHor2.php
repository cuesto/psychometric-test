<?php
require_once 'phplot.php';
function drawBar4($val1,$val2,$val3,$val4,$val5,$img)
{
$data = array(
  array('Body confidence', $val1),   array('Imagination capacity', $val2),
  array('Interpersonal relation skill',  $val3),   array('Problem solving', $val4),array('Emotional maturity', $val5),
);

$plot = new PHPlot(328,159);
$plot->SetImageBorderType('plain'); // Improves presentation in the manual
$plot->SetTitle("");
$plot->SetBackgroundColor('white');

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
$plot->SetDataColors('YellowGreen');
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


function drawBar44($val1,$val2,$val3,$val4,$val5,$img)
{
    
    include("wp-content/plugins/cb-test/result_scripts/libchart/libchart/classes/libchart.php");
    
    $chart = new HorizontalBarChart(328, 159);

	$dataSet = new XYDataSet();
	$dataSet->addPoint(new Point("Body confidence", $val1));
	$dataSet->addPoint(new Point("Imagination capacity", $val2));
	$dataSet->addPoint(new Point("Interpersonal relation skill", $val3));
    $dataSet->addPoint(new Point("Problem solving", $val4));
    $dataSet->addPoint(new Point("Emotional maturity", $val5));
	$chart->setDataSet($dataSet);

	$chart->setTitle("");
	$chart->render("wp-content/plugins/cb-test/result_scripts/tmp/$img");
}
?>