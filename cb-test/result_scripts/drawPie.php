<?php
require_once 'phplot.php';

function drawPie($val1,$val2,$img)
{
     $data = array(
     array('', $val1,$val2)
     );

    $img=$img;

    $plot = new PHPlot(300,147);
    $plot->SetImageBorderType('none');
    $plot->SetDataType('text-data');
    $plot->SetDataValues($data);
    $plot->SetPlotType('pie');
    $plot->SetIsInline(true);

    $plot->SetOutputFile($img);
    $plot->DrawGraph();
}


function drawPie1($val1, $name1, $val2, $name2, $img)
{
    //graph
   	include("wp-content/plugins/cb-test/result_scripts/libchart/libchart/classes/libchart.php");
	$chart = new PieChart(370,200);
    $chart->getPlot()->getPalette()->setPieColor(array(
    		new Color(246, 147, 96),
    		new Color(152, 54, 73)
      ));
	$dataSet = new XYDataSet();
    if($val1>$val2)
    {
    	$dataSet->addPoint(new Point($name1, $val1));
    	$dataSet->addPoint(new Point($name2, $val2));
	} else {
    	$dataSet->addPoint(new Point($name2, $val2));    	   
    	$dataSet->addPoint(new Point($name1, $val1));
	}
    $chart->setDataSet($dataSet);
    	$chart->setTitle("");
	$chart->render("wp-content/plugins/cb-test/result_scripts/tmp/$img");
        
}

?>