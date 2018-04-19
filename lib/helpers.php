<?php

function base_dir() {
  return $GLOBALS['jul_base_dir'];
}

function base_path() {
  return $GLOBALS['jul_base_path'];
}

function to_home() {
  return base_dir();
}

/**
 * Returns the destination of a route string, as defined in lib/routing.php.
 * Routes start with a @ character. Anything other than a route is returned verbatim.
 * This means you can pass either a @route and get the proper route URL, or just pass a full URL by itself.
 */
function to_route($route) {
  if ($route[0] !== '@') return $route;
  return $GLOBALS['jul_routes'][$route];
}

/**
 * Returns an absolute link to an image file.
 */
function dir_images($file='') {
  return "{base_dir()}/images/{$file}";
}
