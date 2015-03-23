<?php

	// Includes ===============================================================
	
	require "clowder.php";

    // Constantes =============================================================
    
    define("API_KEY", '29rTtCyrBfZvABBMMbne');
    	
	// Test ===================================================================
	
	$load = sys_getloadavg();

	$clowder = new Clowder(API_KEY);
	
	$clowder->ok([
		'name' => 'CPU Percentage',
		'value' => $load[0],
		'frequency' => new DateInterval('PT30S')
	]);
	
	$clowder->ok([
		'name' => 'Memory Utilization',
		'value' => memory_get_usage()
	]);

	// EOF ====================================================================
?>
