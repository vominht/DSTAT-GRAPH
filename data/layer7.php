<?php
header("Access-Control-Allow-Origin: *");
header("Content-type: text/json");
$x = time() * 1000;
$file="layer7-logs";
$linecount = 0;
$handle = fopen($file, "r");
while(!feof($handle)){
  $line = fgets($handle);
  $linecount += 1;
}
fclose($handle);
file_put_contents("layer7-logs", "");
$y = $linecount-1;
echo $y;
?>