<?php
$file = file_get_contents("torque.json");
$json = json_decode($file);

$tempArray = json_encode($_GET);
array_push($json, json_decode($tempArray));

$fh = fopen("/var/www/torque.json", 'w') or die("Can't open file!");
fwrite($fh, json_encode($json));
fclose($fh);

print "OK!";
?>