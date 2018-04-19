<?php

	require_once '../lib/function.php';
	$windowtitle	= "FAQ / Rules -- {$GLOBALS['jul_settings']['board_name']}";
	require_once '../lib/layout.php';


	$topiclist	= "";
	$faq		= "";
  	if ($x_hacks['host']) {
		print "$header<br>
			$tblstart
				<tr>$tccellh>FAQ and Rules</td></tr>
				<tr>$tccell1l>Generally, this forum is for a small group of people that know each other well. You should probably think twice about registering if you don't know who the regulars are already.
				</td></tr>
			$tblend";

	} else {

	$faq	.= faqformat("darules", "The Rules", "
		Our rules are really <i>really simple</i>, if you take the time to learn them. And you <i>should!</i>
		<ol>
			<li><b>Don't be an asshole.</b> If you don't have something constructive to say, <i>don't say it!</i> This is the big one.
			<li><b>This forum's official language is English</b>. Don't use chat-speak or other unfunny gimmicks.
			<li><b>Double-posting / bumping old threads.</b> Replying within minutes asking if anybody has read your post is a terrible idea. Double posting after a day or two <i>with something new or updated</i> is fine, though. If you want to bump an old thread, contribute something new to it.
			<li><b>Don't get in fights.</b> If you're having trouble with another user, contact an administrator.
			<li><b>Nothing illegal.</b> No warez, illegal crap, etc. There are exceptions for ROMs in certain cases.
			<li><b>The staff has the final say in everything.</b> We can ban you for any reason or no reason at all. Posting here is NOT a right.
		</ol>
		<br>Punishments are given on a case-by-case basis, from 'warning' to 'permanent ban'.
		<br>
		<br>We are a relaxed community, but constantly breaking the rules will get you banned fast.
		<br>
		<br>If you have any questions, feel free to ask <a href='{$GLOBALS['jul_views_path']}/memberlist.php?pow=3'>one of the admins</a> for help.
	");


	$faq	.= faqformat("aboot", "About Jul", "
		Jul itself is a community made up of people who predominantly like to just hang around friendlies and talk about whatever, though we also like games and occasionally anime/other things.
	");

	$faq	.= faqformat("newbies", "I'm new here. Where should I start?", "
		Always, by reading the rules... but since you're here, it's <i>probably</i> a safe bet that you've already done that. (If you haven't, <i>now is a great time.</i>)
		<br>
		<br>Once you've done that, <a href='{$GLOBALS['jul_views_path']}/register.php'>sign up for an account</a> (or <a href='{$GLOBALS['jul_views_path']}/login.php'>log in</a> if you've already made one). It's simple and very easy to do. After you're registered, you're more than welcome to just <a href='{$GLOBALS['jul_views_path']}/forum.php?id=1'>jump in and say hi</a> by posting in the Introductions thread, or even making your own. We're friendly people and won't bite (usually). Let us know about yourself, how you found us, or whatever's on your mind &mdash; or just jump in and start contributing to discussions.
	");

	$faq	.= faqformat("layoutlowdown", "What are post layouts?", "
	Post layouts are like signatures on other forums, but on steroids. Rather than just some text, an image, and maybe a link, post layouts allow you to style your <em>entire post</em>! You too can turn your wonderful contributions into a GeoCities&trade;-esque abomination.
	<br>
	<br>You can enable or disable the showing of post layouts by choosing the relevant option in your <a href='{$GLOBALS['jul_views_path']}/editprofile.php'>profile settings</a>.
	<br>
	<br>(Particularly egregious/unreadable layouts will be removed by admins. Abuse of this feature will lead to you no longer being able to use one.)
	<br>
	<br>You can customize your layout with fun facts about your statistics by using &amp;tags&amp;, outlined below.
	");

	$faq	.= faqformat("tags", "What are &amp;tags&amp;?", "
		These are variables that can be used in your post header or signature. Once you post, they'll get replaced with a value depending on the tag used.
		<br>
		<br>
		<table class='table font' cellspacing='0' style='width: auto; margin: 0 auto;'>
		  <tr>
		    <td class='tbl tdbgh center'>Tags</td>
		    <td class='tbl tdbgh center'>Description</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>/me</td>
		    <td class='tbl tdbg1'>Your username (must have a space after it)</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;date&amp;</td>
		    <td class='tbl tdbg1'>Current date</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;numdays&amp;</td>
		    <td class='tbl tdbg1'>Number of days since registration</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;numposts&amp;</td>
		    <td class='tbl tdbg1'>Current post count</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;rank&amp;</td>
		    <td class='tbl tdbg1'>Current rank, according to your amount of posts</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;postrank&amp;</td>
		    <td class='tbl tdbg1'>Post ranking</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;5000&amp;</td>
		    <td class='tbl tdbg1'>Posts left until you have 5000</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;10000&amp;</td>
		    <td class='tbl tdbg1'>Posts left until you have 10000</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;20000&amp;</td>
		    <td class='tbl tdbg1'>Posts left until you have 20000</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;30000&amp;</td>
		    <td class='tbl tdbg1'>Posts left until you have 30000</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;exp&amp;</td>
		    <td class='tbl tdbg1'>EXP</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expgain&amp;</td>
		    <td class='tbl tdbg1'>EXP gain per post</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expgaintime&amp;</td>
		    <td class='tbl tdbg1'>Seconds for 1 EXP when idle</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expdone&amp;</td>
		    <td class='tbl tdbg1'>EXP done in the current level</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expdone1k&amp;</td>
		    <td class='tbl tdbg1'>EXP done / 1000</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expdone10k&amp;</td>
		    <td class='tbl tdbg1'>EXP done / 10000</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expnext&amp;</td>
		    <td class='tbl tdbg1'>Amount of EXP left for next level</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expnext1k&amp;</td>
		    <td class='tbl tdbg1'>EXP needed / 1000</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expnext10k&amp;</td>
		    <td class='tbl tdbg1'>EXP needed / 10000</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;exppct&amp;</td>
		    <td class='tbl tdbg1'>Percentage of EXP done in the level</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;exppct2&amp;</td>
		    <td class='tbl tdbg1'>Percentage of EXP left in the level</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;level&amp;</td>
		    <td class='tbl tdbg1'>Level</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;lvlexp&amp;</td>
		    <td class='tbl tdbg1'>Total EXP amount needed for next level</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;lvllen&amp;</td>
		    <td class='tbl tdbg1'>EXP needed to go through the current level</td>
		  </tr>
		</table>
	");

	$faq	.= faqformat("bbcode", "What is BBcode?", doreplace2("
		BBcode is a simple syntax which you can use on your posts to format the text or add images and videos. Below is a list of the supported tags:
		<br>
		<br>
		<table class='table font' cellspacing='0' style='width: auto; margin: 0 auto;'>
		  <tr>
		    <td class='tbl tdbgh center' style='width: 50%;'>BBcode</th>
		    <td class='tbl tdbgh center' style='width: 50%;'>Result</th>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[b<!-- -->]Bolded text.[/b<!-- -->]</td>
		    <td class='tbl tdbg1'>[b]Bolded text.[/b]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[i<!-- -->]Italicized text.[/i<!-- -->]</td>
		    <td class='tbl tdbg1'>[i]Italicized text.[/i]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[u<!-- -->]Underlined text.[/u<!-- -->]</td>
		    <td class='tbl tdbg1'>[u]Underlined text.[/u]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[s<!-- -->]Strikethrough text.[/s<!-- -->]</td>
		    <td class='tbl tdbg1'>[s]Strikethrough text.[/s]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[abbr<!-- -->=Basic Input/Output System]BIOS[/abbr<!-- -->]</td>
		    <td class='tbl tdbg1'>[abbr=Basic Input/Output System]BIOS[/abbr]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[sp<!-- -->=terrible]Great[/sp<!-- -->] software.</td>
		    <td class='tbl tdbg1'>[sp=terrible]Great[/sp] software.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[url<!-- -->]http://example.com/[/url<!-- -->]</td>
		    <td class='tbl tdbg1'>[url]http://example.com/[/url]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[url<!-- -->=http://example.com/]Link text here.[/url<!-- -->]</td>
		    <td class='tbl tdbg1'>[url=http://example.com/]Link text here.[/url]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[img<!-- -->]https://tcrf.net/images/c/c4/SMB2-smiley.png[/img<!-- -->]</td>
		    <td class='tbl tdbg1'>[img]https://tcrf.net/images/c/c4/SMB2-smiley.png[/img]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[red<!-- -->]Red color.[/color<!-- -->]</td>
		    <td class='tbl tdbg1'>[red]Red color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[green<!-- -->]Green color.[/color<!-- -->]</td>
		    <td class='tbl tdbg1'>[green]Green color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[blue<!-- -->]Blue color.[/color<!-- -->]</td>
		    <td class='tbl tdbg1'>[blue]Blue color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[orange<!-- -->]Orange color.[/color<!-- -->]</td>
		    <td class='tbl tdbg1'>[orange]Orange color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[yellow<!-- -->]Yellow color.[/color<!-- -->]</td>
		    <td class='tbl tdbg1'>[yellow]Yellow color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[pink<!-- -->]Pink color.[/color<!-- -->]</td>
		    <td class='tbl tdbg1'>[pink]Pink color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[white<!-- -->]White color.[/color<!-- -->]</td>
		    <td class='tbl tdbg1'>[white]White color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[black<!-- -->]Black color.[/color<!-- -->] (bad idea)</td>
		    <td class='tbl tdbg1'>[black]Black color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[quote<!-- -->=user]Quoted text.[/quote<!-- -->]</td>
		    <td class='tbl tdbg1'>[quote=user]Quoted text.[/quote]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[code<!-- -->]Sample &lt;b&gt;code&lt;/b&gt;.[/code<!-- -->]</td>
		    <td class='tbl tdbg1'>[code]Sample <b>code</b>.[/code]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[spoiler<!-- -->]Spoiler text.[/spoiler<!-- -->]</td>
		    <td class='tbl tdbg1'>[spoiler]Spoiler text.[/spoiler]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[youtube<!-- -->]BrQn-O_zFRc[/youtube<!-- -->] (video ID)</td>
		    <td class='tbl tdbg1'>A YouTube embed.</td>
		  </tr>
		</table>
	"));

	$faq	.= faqformat("halp", "I've got a question and I need some help, or I found a bug somewhere.", "
		<a href='{$GLOBALS['jul_views_path']}/forum.php?id=39'>Post it in the forum here</a>, or alternatively just message the <a href='{$GLOBALS['jul_views_path']}/sendprivate.php?userid=1'>main administrator</a>. If it's a security bug in the code, we <i>really</i> recommend the latter.
	");

	$faq	.= faqformat("band", "I've been banned. Now what?", "
		You can try checking your title (under your username in your posts) to find out the reason and when it expires. If there's no expiration, it's probably <i>permanent</i>. If you're post due for unbanning, <a href='{$GLOBALS['jul_views_path']}/sendprivate.php?userid=1'>let an admin know</a> and they'll take care of it.
		<br>
		<br>On the other hand, if it's permanent, you can always try to show us you've changed and request a <i>second chance</i>... but any further antics after that will usually get your account <b>deleted</b>.
	");

	$faq	.= faqformat("cantpass", "I've lost/forgotten my password. Now what?", "
		The best thing you can do is to <a href='{$GLOBALS['jul_views_path']}/profile.php?id=1'>contact Xkeeper directly</a>. He can help you get it fixed.
	");


	$faq	.= faqformat("frosteddonut", "I want to throw money at you guys. How do I do that?", "
			Really? How generous.
		<br>
		<br>Donations with this button go straight to the hosting bill, and we can't withdraw them, so you don't have to worry about us secretly buying drugs or other fancy stuff with your money.
		<br>
		<br>However, there is a slight fee involved, so suffice it to say it's often better to donate $20 at once intead of ten $2 donations.
		<br>
		<br><a href=\"http://www.dreamhost.com/donate.cgi?id=11617\"><img border=\"0\" alt=\"Donate towards Jul's web hosting!\" title='Click this and give us your money.' src=\"https://secure.newdream.net/donate1.gif\" /></a>
		<br>
		<br>Thanks in advance.
		<br>
		<br>At some point we plan on getting a 'donor star' for those who paid our bills... other than that, there isn't really any other benefit than a warm, fuzzy feeling.
	");


	$faq	.= faqformat("leganese", "Legal Crap", "
		The site does not own and cannot be held responsible for statements made by members on the forum. This site is offered as-is to the user. Any statements made on the board may be altered or removed at the discretion of the staff.
		<br>
		<br>We do not sell member information like e-mail addresses or passwords to any third party. Data entered into user profiles is completely optional and may be left out at the user's discretion; however, doing so may complicate matters such as account recovery.
	");


	print "$header<br>

		$tblstart
			<tr>$tccellh>FAQ and Rules</td></tr>
			<tr>$tccell1l><b>Table of Contents</b>:
			<ul>
				$topiclist
			</ul></td></tr>
		$tblend

		$faq
		";


	}

		print "
	<br><br>

	$footer
  ";
  printtimedif($startingtime);





	function faqformat($a, $title, $content) {
		global $tblstart, $tccellh, $tccell1l, $tblend, $topiclist;

		$topiclist	.= "\n\t\t<li><a href='#$a'>$title</a></li>";

		return "<br><br><a name='$a'></a>
		$tblstart
			<tr>$tccellh><div style='float: right;'>[<a href='#top'>^</a>]</div><b>$title</b></td></tr>
			<tr>$tccell1l style='padding: 4px;'>$content
			</td></tr>
		$tblend
		";
	}


?>
