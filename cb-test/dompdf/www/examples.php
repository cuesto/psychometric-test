<?php

require_once("../dompdf_config.inc.php");
   if ( get_magic_quotes_gpc() )
    $_POST["html"] = stripslashes($_POST["html"]);
  
  $dompdf = new DOMPDF();
  $url = 'sa.htm';
  if(function_exists('file_get_contents'))//
    $content = file_get_contents($url);
  else
    $content = "Function doesnt exist";
  $dompdf->load_html($content);
  $dompdf->set_paper('a4','landscape' );
  $dompdf->render();

  $dompdf->stream("dompdf_out.pdf", array("Attachment" => false));

  exit(0);
?>