<?php

	require 'lib/function.php';
	$windowtitle	= "FAQ / Rules -- $boardname";
	require 'lib/layout.php';


	$topiclist	= "";
	$faq		= "";

	$faq	.= faqformat("aboot", "About Jul, the community", "
		Jul itself is a community made up of people who predominantly like to just hang around friendlies and talk about whatever, though we also like games and occasionally anime/other things.
		<br>
		<br>It is a community that has gone on for over 10 years, founded early July 2007. It is based off of another (defunct) community that started in early 2001.
		<br>
		<br>While we're an old group, we always welcome new folks. Feel free to drop in and say hello.
	");

	$faq	.= faqformat("aboot", "About the forum", "
		This forum is based off of 2001-era custom software, and is pretty different from most other forums on the internet. <strong>There are no push notifications, no e-mail reminders, no 'we miss you' nags, no popups, no ads, no apps, and no tracking.</strong> What you see is exactly what you get.
		<br>
		<br>That being said, here is a quote from a <a href='https://www.doomworld.com/cacowards/2018'>different site</a> that explains us well:
		<blockquote>I think it is important in this moment to restate that [this site] is an independently owned and operated website; it is intended as a long term, not-for-profit informational and historical resource; we are dedicated to treating all people with respect; it will never be sunsetted or deprecated or paywalled; and we do not vacuum up your personal information, much less profit from it. If you despair for the future of the Internet, consider that [this site], and thousands of small websites just like it, continue to exist and thrive in the spirit of discovery and camaraderie in which the Internet was first conceived.</blockquote>
	");

	$faq	.= faqformat("newbies", "I'm new here. Where should I start?", "
		Always, by reading the rules... but since you're here, it's <i>probably</i> a safe bet that you've already done that. (If you haven't, <i>now is a great time.</i>)
		<br>
		<br>Once you've done that, <a href='register.php'>sign up for an account</a> (or <a href='login.php'>log in</a> if you've already made one). It's simple and very easy to do. After you're registered, you're more than welcome to just <a href='forum.php?id=1'>jump in and say hi</a> by posting in the Introductions thread, or even making your own. We're friendly people and won't bite (usually). Let us know about yourself, how you found us, or whatever's on your mind &mdash; or just jump in and start contributing to discussions.
	");

	$faq	.= faqformat("darules", "The Rules", "
		Our rules are really <em>really simple</em>:
		<ol>
			<li><strong>Don't be a jerk.</strong> If you don't have something constructive to say, <em>don't say it!</em>
			<li><strong>No slurs, hate speech, or homo-/trans-phobia.</strong> If you can't respect your fellow posters, you aren't welcome here.
			<li><strong>Post legibly</strong>. Keep it readable &mdash; you don't have to be perfect, but be understandable and don't post like this is an AOL chatroom.
			<li><strong>Don't spam.</strong> Posting over and over without adding to a conversation is annoying.
			<li><strong>Don't get in fights.</strong> If you're having trouble with another user, contact an administrator.
			<li><strong>No illegal content.</strong> Don't post stuff that would get you (or us) in legal trouble.
		</ol>
		However, <strong>the admins have the final say in everything!</strong> We can ban you for any reason, or no reason at all.
		<br>
		<br>Breaking the rules will resort in whatever punishment we feel is worthy, from giving you a warning to banning you forever. Posting here is NOT a right.
		<br>
		<br>If you have any questions, feel free to ask <a href='memberlist.php?pow=3'>one of the admins</a> for help.
	");

	$faq	.= faqformat("layoutlowdown", "What are post layouts?", "
	Post layouts are like signatures on other forums, but on steroids. Rather than just some text, an image, and maybe a link, post layouts allow you to style your <em>entire post</em>! You too can turn your wonderful contributions into a GeoCities&trade;-esque abomination.
	<br>
	<br>You can customize your layout with fun facts about your statistics by using &amp;tags&amp;, outlined below.
	<br>
	<br>You can enable or disable others' post layouts in your <a href='editprofile.php'>profile settings</a>.
	<br>
	<br>If you make a post layout that interferes with the board's interface, is particularly annoying, is hard-to-read, or is just awful, it will be removed. If you continue to do this, your ability to use them will be revoked. Malfunctioning layouts (due to broken images or CSS) may also be removed.
	");

	$faq	.= faqformat("tags", "What are &amp;tags&amp;?", "
		These are variables that can be used in your post header or signature. Once you post, they'll get replaced with a value depending on the tag used.
		<br>
		<br>
		<table class='table font' cellspacing='0' style='width: auto; margin: 0 auto;'>
		  <tr>
		    <td class='tbl tdbgh center'>Tag</td>
		    <td class='tbl tdbgh center'>Description</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>/me</td>
		    <td class='tbl tdbg1'>Your username (must have a space after it), like IRC.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;date&amp;</td>
		    <td class='tbl tdbg1'>The current date</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;numdays&amp;</td>
		    <td class='tbl tdbg1'>Number of days since you registered</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;numposts&amp;</td>
		    <td class='tbl tdbg1'>How many posts you've made</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;rank&amp;</td>
		    <td class='tbl tdbg1'>Current rank, according to your amount of posts</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;postrank&amp;</td>
		    <td class='tbl tdbg1'>Your 'ranking', by post count, among all members</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;5000&amp;</td>
		    <td class='tbl tdbg1'>Posts left until you have 5,000</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;10000&amp;</td>
		    <td class='tbl tdbg1'>Posts left until you have 10,000</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;20000&amp;</td>
		    <td class='tbl tdbg1'>Posts left until you have 20,000</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;30000&amp;</td>
		    <td class='tbl tdbg1'>Posts left until you have 30,000</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;level&amp;</td>
		    <td class='tbl tdbg1'>Your current level.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;exp&amp;</td>
		    <td class='tbl tdbg1'>Your current EXP.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expgain&amp;</td>
		    <td class='tbl tdbg1'>How much EXP you gain per post.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expgaintime&amp;</td>
		    <td class='tbl tdbg1'>How many seconds it takes to get 1 EXP naturally.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expdone&amp;</td>
		    <td class='tbl tdbg1'>How much EXP you've done in your current level.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expdone1k&amp;</td>
		    <td class='tbl tdbg1'>The above, divided by 1,000.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expdone10k&amp;</td>
		    <td class='tbl tdbg1'>The above, divided by 10,000.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expnext&amp;</td>
		    <td class='tbl tdbg1'>How much EXP you have left until the next level.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expnext1k&amp;</td>
		    <td class='tbl tdbg1'>The above, divided by 1,000.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;expnext10k&amp;</td>
		    <td class='tbl tdbg1'>The above, divided by 10,000.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;exppct&amp;</td>
		    <td class='tbl tdbg1'>How much EXP you've done in your current level, in percent.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;exppct2&amp;</td>
		    <td class='tbl tdbg1'>How much EXP you have left in your current level, in percent.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;lvlexp&amp;</td>
		    <td class='tbl tdbg1'>Cumulative EXP for your next level.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;lvllen&amp;</td>
		    <td class='tbl tdbg1'>EXP needed to go through your current level.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>&amp;mood&amp;</td>
		    <td class='tbl tdbg1'>Selected mood number for this post (default: 0)</td>
		  </tr>
		</table>
		For an explanation of how the Level and EXP tags work, hover over the highlighted parts below. (The percentages are useful when creating 'EXP bars', as you can use them as a width value.)
		<br>
		<br>Level <abbr title='&amp;level&amp;'>10</abbr> &mdash; <abbr title='&amp;exp&amp;'>1040</abbr> EXP (<abbr title='&amp;expgain&amp;'>20</abbr> per post, 1 EXP per <abbr title='&amp;expgaintime&amp;'>300</abbr> sec.)
		<br>(Next level at <abbr title='&amp;lvlexp&amp;'>1200</abbr> in <abbr title='&amp;expnext&amp;'>160</abbr> EXP. (<abbr title='&amp;expdone&amp;'>40</abbr>/<abbr title='&amp;lvllen&amp;'>200</abbr> EXP) &mdash; <abbr title='&amp;exppct&amp;'>20</abbr>% done, <abbr title='&amp;exppct2&amp;'>80</abbr>% left
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
		    <td class='tbl tdbg2'>[b&#93;Bolded text.[/b&#93;</td>
		    <td class='tbl tdbg1'>[b]Bolded text.[/b]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[i&#93;Italicized text.[/i&#93;</td>
		    <td class='tbl tdbg1'>[i]Italicized text.[/i]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[u&#93;Underlined text.[/u&#93;</td>
		    <td class='tbl tdbg1'>[u]Underlined text.[/u]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[s&#93;Strikethrough text.[/s&#93;</td>
		    <td class='tbl tdbg1'>[s]Strikethrough text.[/s]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[abbr<!-- -->=Basic Input/Output System]BIOS[/abbr&#93;</td>
		    <td class='tbl tdbg1'>[abbr=Basic Input/Output System]BIOS[/abbr]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[sp<!-- -->=terrible]Great[/sp&#93; software.</td>
		    <td class='tbl tdbg1'>[sp=terrible]Great[/sp] software.</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[url&#93;http://example.com/[/url&#93;</td>
		    <td class='tbl tdbg1'>[url]http://example.com/[/url]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[url<!-- -->=http://example.com/]Link text here.[/url&#93;</td>
		    <td class='tbl tdbg1'>[url=http://example.com/]Link text here.[/url]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[img&#93;https://jul.rustedlogic.net/images/smilies/toot.png[/img&#93;</td>
		    <td class='tbl tdbg1'>[img]https://jul.rustedlogic.net/images/smilies/toot.png[/img]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[red&#93;Red color.[/color&#93;</td>
		    <td class='tbl tdbg1'>[red]Red color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[green&#93;Green color.[/color&#93;</td>
		    <td class='tbl tdbg1'>[green]Green color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[blue&#93;Blue color.[/color&#93;</td>
		    <td class='tbl tdbg1'>[blue]Blue color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[orange&#93;Orange color.[/color&#93;</td>
		    <td class='tbl tdbg1'>[orange]Orange color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[yellow&#93;Yellow color.[/color&#93;</td>
		    <td class='tbl tdbg1'>[yellow]Yellow color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[pink&#93;Pink color.[/color&#93;</td>
		    <td class='tbl tdbg1'>[pink]Pink color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[white&#93;White color.[/color&#93;</td>
		    <td class='tbl tdbg1'>[white]White color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[black&#93;Black color.[/color&#93; (bad idea)</td>
		    <td class='tbl tdbg1'>[black]Black color.[/color]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[quote<!-- -->=user]Quoted text.[/quote&#93;</td>
		    <td class='tbl tdbg1'>[quote=user]Quoted text.[/quote]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[code&#93;Sample &lt;b&gt;code&lt;/b&gt;.[/code&#93;</td>
		    <td class='tbl tdbg1'>[code]Sample <b>code</b>.[/code]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[spoiler&#93;Spoiler text.[/spoiler&#93;</td>
		    <td class='tbl tdbg1'>[spoiler]Spoiler text.[/spoiler]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[spoileri&#93;Spoiler text.[/spoileri&#93;</td>
		    <td class='tbl tdbg1'>[spoileri]Spoiler text.[/spoileri]</td>
		  </tr>
		  <tr>
		    <td class='tbl tdbg2'>[youtube&#93;BrQn-O_zFRc[/youtube&#93; (video ID)</td>
		    <td class='tbl tdbg1'>A YouTube embed.</td>
		  </tr>
		</table>
	"));

	$faq	.= faqformat("halp", "I've got a question and I need some help, or I found a bug somewhere.", "
		<a href='forum.php?id=39'>Post it in the forum here</a>, or alternatively just message the <a href='sendprivate.php?userid=1'>main administrator</a>. If it's a security bug in the code, we <i>really</i> recommend the latter.
	");

	$faq	.= faqformat("band", "I've been banned. Now what?", "
		You can try checking your title (under your username in your posts) to find out the reason and when it expires. If there's no expiration, it's probably <i>permanent</i>. If you're post due for unbanning, <a href='sendprivate.php?userid=1'>let an admin know</a> and they'll take care of it.
		<br>
		<br>On the other hand, if it's permanent, you can always try to show us you've changed and request a <i>second chance</i>... but any further antics after that will usually get your account <b>deleted</b>.
	");

	$faq	.= faqformat("cantpass", "I've lost/forgotten my password.", "
		The best thing you can do is to <a href='profile.php?id=1'>contact Xkeeper directly</a>. They can help you get it reset. You'll need whatever information you have about your account, including your current IP address, the e-mail you added to your profile (if any), and any other information that can help confirm your identity.
	");


	$faq	.= faqformat("leganese", "Legal Crap / Privacy Policy / et cetera", "
		The site does not own, and cannot be held responsible for, statements made by members on the forum. This site is offered as-is to the user. Any statements made on the board may be altered or removed at the discretion of the staff.
		<br>
		<br>We do not automatically collect or store personal information, with the exception of IP addresses used when registering, logging in, and posting. Unlike pretty much every other site on the internet, we do not use cookies with the sole exception of authenticating a user should they wish to sign in. That means we don't have to show you a cookie popup. Nice, huh? Pretty rare these days.
		<br>
		<br>All information on this site, excepting username, password, and IP address, is optional, and is provided by the user. If you do not want your information on this site, <em>don't submit it</em>.
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
