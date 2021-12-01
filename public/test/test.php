<?php 
include dirname($_SERVER['DOCUMENT_ROOT'])."/lib/test.lib.php"; 

$arr = ['test01', 'test02', 'test03'];
$test = array_map(fn($m) => "('$m')" , $arr);
$str = implode(",", $test); 
dumping($str);

