<?php
$n = $_GET['n'];
$l = $_GET['l'];
$f = $_GET['f'];
 $len=strlen($n .'');
 if($len<$l){
   $ofs=$l-$len;
   $len=$l;
 }
 if(!$f) $f='numnes';
 $gfx=ImageCreateFromPNG("numgfx/$f.png");
 $img=ImageCreate($len*8,8);
 ImageCopy($img,$gfx,0,0,104,0,1,1);
 for($i=0;$i<$len;$i++){
  switch($n[$i]){
    case '/': $d=10; break;
    case 'N': $d=11; break;
    case 'A': $d=12; break;
    case '-': $d=13; break;
    default: $d=$n[$i];
  }
  ImageCopy($img,$gfx,($i+$ofs)*8,0,$d*8,0,8,8);
 }
 Header('Content-type:image/png');
 if ($f == "numdeath") {
	 $ctp	= 1;
 } else {
	 $ctp	= 0;
 }
 ImageColorTransparent($img,$ctp);
 ImagePNG($img);
 ImageDestroy($img);
?>