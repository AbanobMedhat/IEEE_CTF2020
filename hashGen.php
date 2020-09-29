<?php
$radd = 982;
$filename = "test5";
$time = date('Y-m-d H:i:s');
// $time2 = date();
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");

for ($i=1; $i <=1000 ; $i++) 
{ 
	$md= md5($i."2020-09-27 13:50:21".$filename."0x4148fo");
	$inp= $md."."."php"."\n";
	fwrite($myfile, $inp);
	

}

fclose($myfile);