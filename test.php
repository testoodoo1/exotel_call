<?php
$a = array_fill(1, 100, 'banana');
$cpm = 2;
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
			$execution_time = ($end - $start);
			echo $execution_time;
			$start = microtime(true);
			echo "\n";
		}
	}
}
?>