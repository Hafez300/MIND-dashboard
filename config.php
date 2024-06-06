<?php

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

date_default_timezone_set("Africa/Cairo");
$now = date("Y-m-d H:i:s");

$conn = mysqli_connect("localhost", "root", "","ihoneyherb_db") or die(mysqli_error());


$baseurl = "https://ihoneyherb.com/admin-test/";


mysqli_query($conn,"SET NAMES 'utf8mb4'");
mysqli_query($conn,'SET CHARACTER SET utf8mb4_bin');

?>