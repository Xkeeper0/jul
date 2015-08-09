<?php

	$x_hacks['mmdeath']	= (1277820000 + 3600) - time();
	if (!$getdoom) {
		$x_hacks['mmdeath']	= max(0, $x_hacks['mmdeath']);
		print $x_hacks['mmdeath'];
		exit;
	}
		