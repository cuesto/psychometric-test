<?php
require_once 'phplot.php';

function drawBar2($val1,$val2,$val3,$val4,$val5,$img)
{
$data = array(
  array('Body confidence', $val1),   array('Imag.capacity', $val2),
  array('Interpersonal skill',  $val3),   array('Problem solving', $val4),array('Emotional maturity', $val5),
);

$plot = new PHPlot(534, 235);
$plot->SetImageBorderType('plain');
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetPlotAreaWorld(NULL,0,NULL,100);
$plot->SetDataColors('YellowGreen');
$plot->SetDataValues($data);
        $plot->SetFontTTF('x_title', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
        $plot->SetFontTTF('title', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
        $plot->SetFontTTF('generic', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
        $plot->SetFontTTF('legend', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
        $plot->SetFontTTF('x_label', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
        $plot->SetFontTTF('y_label', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
        $plot->SetFontTTF('y_title', 'wp-content/plugins/cb-test/result_scripts/calibrib.ttf', 10);
$plot->SetTitle("<30:Needs work|  30-60:Good|  >60:Right Track");

$plot->SetShading(3);
          //$plot->SetLegend(array('<30:Needs work', '30-60:Good', '>60:Right Track'));
        
        $plot->SetPlotAreaWorld(NULL, 0, NULL, 100);
         $plot->SetYTickIncrement(30);
      $plot->SetYLabelType('data');
         $plot->SetPrecisionY(0);

$plot->SetIsInline(true);

//create the output file
  $plot->SetOutputFile("wp-content/plugins/cb-test/result_scripts/tmp/".$img);

$plot->DrawGraph();
}
?>