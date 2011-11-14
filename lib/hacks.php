<?php
	// layout hacks made easy (and clean)
	// TODO: make this load from external files
	$hacks = Array();
	
	$hacks["layout"] = array(
		"ikachan" => function($image = "/images/squid.png") {
			return "<img src='$image' style='position: fixed; left: ".mt_rand(0,100)."%;
				top: ".mt_rand(0,100)."%'>";
		}
	);
