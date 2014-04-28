<?php
$file = file_get_contents("torque.json");

$fh = fopen("/var/www/torque.json", 'a') or die("Can't open file!");
fwrite($fh, json_encode($_GET) . PHP_EOL);
fclose($fh);

print "OK!";
?>