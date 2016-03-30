<?php
include('config.php');
include ('functions.php');

$fileName = "testfile2.txt";
$localFileLocation = "/Users/skolodziej/ownCloud/Projekte/_dev/netscaler/php-ns-framework/";
$fileLocation = "/nsconfig/ssl/";

$authToken = getAuthCookie($nitroNSIP,$nitroUser,$nitroPass);
//$file = getFile($nitroNSIP,$authToken,$fileName,$fileLocation);
//file_put_contents($fileName, $file);

sendFile($nitroNSIP,$authToken,$fileName,$localFileLocation,$fileLocation) 

?>