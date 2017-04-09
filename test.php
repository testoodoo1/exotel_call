<?php
$a = array_fill(1, 100, 'banana');
$cpm = 3;
$i = 0;
$start = microtime(true);

while(true)
{
	$a = array_fill(1, 100, 'banana');
	foreach($a as $b){
			echo $b;
			echo "\n";
			usleep(500000);
			$i++;
		if($i % $cpm == 0){
			echo "Love all\n";
			$end = microtime(true);
			$execution_time = ($end - $start)/60;
			$remaining_time = round(60 - $execution_time);
			echo $remaining_time;
			echo "\n";
			echo $execution_time;
			sleep($remaining_time);
			$start = microtime(true);
			echo "\n";
		}
	}
}
?>