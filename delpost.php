<?php
  require_once 'lib/libs.php';
  print "
$body
<title>Delete Post</title>
$css
<form name=\"delpost\" action=\"editpost.php\" method=\"POST\">
<input type=\"hidden\" name=\"id\" value=\"$pid\">
<input type=\"hidden\" name=\"action\" value=\"delete\">
<center>
 <table height=100% valign=middle><td>
 $tblstart
  $tccell1
  <br>
  Are you sure you want to delete this post?
  <table width=100%>
   <td><center>$inps=submit VALUE=\"Yes\"></center></td>
   <td><center>$inps=submit onClick=\"javascript:window.close();\" VALUE=\"No\"></center></td>
   </form>
  </table>
 $tblend
 </table>
";
?>
