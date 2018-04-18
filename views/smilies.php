<?php
  require '../lib/function.php';
  require '../lib/layout.php';

   $s	= readsmilies();

  print "
$body
<title>Smilies</title>
$css
<center>
 <table height=100% valign=middle><td>
  $tblstart";

	foreach($s as $i => $v) {
		if (!($i % 4)) print "<tr>";

		if ($v) print "$tccell1><img src=\"". $v[1] ."\"></td>$tccell2>". $v[0] ."</td>";
	}



/*"   <tr>
	$tccell1><img src=images/smilies/smile.gif></td>
    $tccell1>:)</td>
	$tccell1><img src=images/smilies/wink.gif></td>
    $tccell1>;)</td>
	$tccell1><img src=images/smilies/biggrin.gif></td>
    $tccell1>:D</td>
	$tccell1><img src=images/smilies/lol.gif></td>
    $tccell1>:LOL:</td>
   </tr><tr>
	$tccell1><img src=images/smilies/glasses.gif></td>
    $tccell1>8-)</td>
	$tccell1><img src=images/smilies/frown.gif></td>
    $tccell1>:(</td>
	$tccell1><img src=images/smilies/mad.gif></td>
    $tccell1>>:</td>
	$tccell1><img src=images/smilies/yuck.gif></td>
    $tccell1>>_<</td>
   </tr><tr>
	$tccell1><img src=images/smilies/tongue.gif></td>
    $tccell1>:P</td>
	$tccell1><img src=images/smilies/wobbly.gif></td>
    $tccell1>:S</td>
	$tccell1><img src=images/smilies/eek.gif></td>
    $tccell1>O_O</td>
	$tccell1><img src=images/smilies/bigeyes.gif></td>
    $tccell1>o_O</td>
   </tr><tr>
	$tccell1><img src=images/smilies/bigeyes2.gif></td>
    $tccell1>O_o</td>
	$tccell1><img src=images/smilies/cute.gif></td>
    $tccell1>^_^</td>
	$tccell1><img src=images/smilies/cute2.gif></td>
    $tccell1>^^;;;</td>
	$tccell1><img src=images/smilies/baby.gif></td>
    $tccell1>~:o</td>
   </tr><tr>
	$tccell1><img src=images/smilies/sick.gif></td>
    $tccell1>x_x</td>
	$tccell1><img src=images/smilies/eyeshift.gif></td>
    $tccell1>:eyeshift:</td>
	$tccell1><img src=images/smilies/vamp.gif></td>
    $tccell1>:vamp:</td>
	$tccell1><img src=images/smilies/blank.gif></td>
    $tccell1>o_o</td>
   </tr><tr>
	$tccell1><img src=images/smilies/cry.gif></td>
    $tccell1>;_;</td>
	$tccell1><img src=images/smilies/dizzy.gif></td>
    $tccell1>@_@</td>
	$tccell1><img src=images/smilies/annoyed.gif></td>
    $tccell1>-_-</td>
	$tccell1><img src=images/smilies/shiftright.gif></td>
    $tccell1>>_></td>
   </tr><tr>
	$tccell1><img src=images/smilies/shiftleft.gif></td>
    $tccell1><_<</td>
	$tccell1><img src=images/smilies/rofl.gif></td>
    $tccell1>:rofl:</td>
	$tccell1><img src=images/smilies/terror.gif></td>
    $tccell1>:terror:</td>
	$tccell1><img src=images/smilies/approved.gif></td>
    $tccell1>:approve:</td>
   </tr><tr>
	$tccell1><img src=images/smilies/denied.gif></td>
    $tccell1>:deny:</td>
	$tccell1><img src=images/smilies/eyeshift2.gif></td>
    $tccell1>:eyeshift2:</td>
	$tccell1><img src=images/smilies/meow.gif></td>
    $tccell1>:meow:</td>
   </tr>
*/
 print "$tblend
 </td></table>
";
?>