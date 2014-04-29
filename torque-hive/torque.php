<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');

// Thrift PHP
$GLOBALS['THRIFT_ROOT'] = dirname(__FILE__).'/src';
require_once( $GLOBALS['THRIFT_ROOT'].'/Thrift.php' );
require_once( $GLOBALS['THRIFT_ROOT'].'/transport/TSocket.php' );
require_once( $GLOBALS['THRIFT_ROOT'].'/protocol/TBinaryProtocol.php' );
require_once( $GLOBALS['THRIFT_ROOT'].'/transport/TBufferedTransport.php' ); 

//hbase thrift
require_once dirname(__FILE__).'/Hbase.php';

//hive thrift
require_once dirname(__FILE__).'/ThriftHive.php';

//open connection
$transport = new TSocket('localhost', 10000);
$protocol = new TBinaryProtocol($transport);
$client = new ThriftHiveClient($protocol);
$transport->open();
 
//show all tables
$client->execute('SHOW TABLES');
$tables = $client->fetchAll();

//Upload data
$hql_data = json_encode($_GET);

$hql_values = array();

$file = "/mnt/ramdisk/torque.json";
$fh = fopen($file, 'w') or die("can't open file");
fwrite($fh, json_encode($_GET) . PHP_EOL);
fclose($fh);

$client->execute('LOAD DATA LOCAL INPATH \'/mnt/ramdisk/torque.json\' INTO TABLE torque_db');
echo 'OK';
?>
