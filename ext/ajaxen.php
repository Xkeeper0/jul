<?php
	header("content-type: text/plain");

	// @Xkeeper: ignore this file entirely.
	if (!IS_AJAX_REQUEST) {
		die("Dude, no. Seriously.");
	}
