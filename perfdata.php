<?php
	require 'lib/function.php';
	require 'lib/layout.php';
	if (!$isadmin) { die(); }
	print "$header<br>";
	
	print adminlinkbar("perfdata.php");
	
	
	
	