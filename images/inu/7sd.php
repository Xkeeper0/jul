<?php

$height = 8;
$charwidth = 6;

$charlist = array(
  0x20 => 0x00, // (space)
  0x22 => 0x22, // "
  0x27 => 0x02, // '
  0x2D => 0x40, // -
          0x10, // .
  0x30 => 0x3F, // 0
          0x06, // 1
          0x5B, // 2
          0x4F, // 3
          0x66, // 4
          0x6D, // 5
          0x7D, // 6
          0x07, // 7
          0x7F, // 8
          0x6F, // 9
  0x3D => 0x48, // =
  0x41 => 0x77, // A
          0x7C, // b
          0x39, // C
          0x5E, // d
          0x79, // E
          0x71, // F
          0x3D, // G
          0x76, // H
          0x30, // I
          0x1E, // J
          0x7A, // K
          0x38, // L
          0x55, // m
          0x37, // N
          0x5C, // o
          0x73, // P
          0x67, // q
          0x50, // r
          0x64, // s
          0x78, // t
          0x3E, // U
          0x2A, // V
          0x6A, // W
          0x49, // x
          0x6E, // y
          0x52, // z
  0x5F => 0x08, // _
);

$shiftt = array();
$shifts = array();
$final = array();

if ($_GET['raw']) {
  $code = intval($_GET['raw']);
  $raw = true;
}
else if ($_GET['s']) $string = strtoupper($_GET['s']);
else $string = '01234 56789 ABCDE FGHIJ KLMNO PQRST UVWXY Z';

if (!$raw) {
  $ssplit = str_split($string);
  $i = 0;
  while ($i < 200 && ($chr = array_shift($ssplit)) !== NULL) {
    if ($chr == '>') {
      $r = hexdec(array_shift($ssplit)) * 17;
      $g = hexdec(array_shift($ssplit)) * 17;
      $b = hexdec(array_shift($ssplit)) * 17;
      $shiftt[$i] = array($r, $g, $b);
    }
    elseif (array_key_exists(ord($chr), $charlist))
      $final[$i++] = ord($chr);
  }
}
$strlen = ($raw) ? 1 : count($final);

$im = @imagecreatetruecolor($strlen*$charwidth, $height) or die('oops, no image.');
$black = imagecolorallocate($im, 0, 0, 1);
imagefill($im, 0, 0, $black);
imagecolortransparent($im, $black);
$shadow = imagecolorallocate($im, 0, 0, 0);

$cl = imagecolorallocate($im, 0, 255, 0);

if ($raw) {
  placeshadow($code, 0);
  place7sd($code, 0);

  header("Content-Type: image/png");
  imagepng($im);
  imagedestroy($im);
  die();
}

foreach ($shiftt as $p => $s)
  $shifts[$p] = imagecolorallocate($im, $s[0], $s[1], $s[2]);

for ($i = 0; $i < $strlen; ++$i) {
  if (array_key_exists($i, $shifts)) $cl = $shifts[$i];
  $chr = $charlist[$final[$i]];
  placeshadow($chr, $i);
  place7sd($chr, $i);
}

header("Content-Type: image/png");
imagepng($im);
imagedestroy($im);

function place7sd($lights, $w) {
  global $im, $cl, $charwidth;
  $w *= $charwidth;
  for ($i = 0x01; $i < 0x80; $i<<=1) {
    if ($lights & $i) switch ($i) {
      case 0x01: imageline($im, $w + 1, 0, $w + 2, 0, $cl); break; //seg a
      case 0x02: imageline($im, $w + 3, 1, $w + 3, 2, $cl); break; //seg b
      case 0x04: imageline($im, $w + 3, 4, $w + 3, 5, $cl); break; //seg c
      case 0x08: imageline($im, $w + 1, 6, $w + 2, 6, $cl); break; //seg d
      case 0x10: imageline($im, $w,     4, $w,     5, $cl); break; //seg e
      case 0x20: imageline($im, $w,     1, $w,     2, $cl); break; //seg f
      case 0x40: imageline($im, $w + 1, 3, $w + 2, 3, $cl); break; //seg g
      default: break;
    }
  }
}

function placeshadow($lights, $w) {
  global $im, $cl, $charwidth;
  $w *= $charwidth;
  for ($i = 0x01; $i < 0x80; $i<<=1) {
    if ($lights & $i) switch ($i) {
      case 0x01: imageline($im, $w + 2, 1, $w + 3, 1, $shadow); break; //seg a
      case 0x02: imageline($im, $w + 4, 2, $w + 4, 3, $shadow); break; //seg b
      case 0x04: imageline($im, $w + 4, 5, $w + 4, 6, $shadow); break; //seg c
      case 0x08: imageline($im, $w + 2, 7, $w + 3, 7, $shadow); break; //seg d
      case 0x10: imageline($im, $w + 1, 5, $w + 1, 6, $shadow); break; //seg e
      case 0x20: imageline($im, $w + 1, 2, $w + 1, 3, $shadow); break; //seg f
      case 0x40: imageline($im, $w + 2, 4, $w + 3, 4, $shadow); break; //seg g
      default: break;
    }
  }
}

?>