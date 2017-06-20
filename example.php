<?php 
	require_once('Ip2.php');
	
	$ip2 = new \ip2iq\Ip2();
	
	$rounds = 1000;
	
	$t1 = microtime(true);
	for($i = 0; $i < $rounds; $i++) {
		$ip = rand(0, 255).'.'.rand(0, 255).'.'.rand(0, 255).'.'.rand(0, 255);
		$country_code = $ip2->country($ip);
		print "$ip => $country_code\n";
	}
	print "\n{$rounds} round(s) took ".(microtime(true)-$t1)." sec(s)\n";

?>