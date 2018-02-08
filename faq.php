<?php

	require 'lib/function.php';
	$windowtitle	= "FAQ / Rules -- $boardname";
	require 'lib/layout.php';


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
			<li><b>Don't be a dick.</b> If you don't have something constructive to say, <i>don't say it!</i> This is the big one.
			<li><b>This forum's official language is English</b>. You're welcome to use other languages (if you at least post a machine translation), though. 'IM' or 'l33t' speak (such as 'u r dum lolol') isn't tolerated.
			<li><b>Be careful about bumping old threads.</b> You should only do so if you're contributing something major and new to the topic, doubly so for general discussions. If it's a hack thread, it's usually OK.
			<li><b>Be careful when double posting.</b> Replying within minutes asking if anybody has read your post is a terrible idea. Double posting after a day or two <i>with something new or updated</i> is fine, though.
			<li><b>Let the staff handle things.</b> Don't try to do our jobs for us &mdash; we'll handle problem users.
			<li><b>Don't post NSFW content without tagging it!</b> Not doing so is an instant ban. In general: <i>Think before you link.</i>
			<li><b>The staff have the final say in everything.</b> If we tell you to do something, do it. <b>No exceptions.</b>
		</ol>
		And some rules that are <i>mostly</i> specific to the ROM Hacking fora but still a good idea to follow everywhere else:
		<ol>
			<li><b>Read the stickies.</b> They're there for a reason.</li>
			<li><b>No ROM links/ROM requests!</b> If you need to upload a hack, use a patch; either IPS, UPS, or any of the other formats.</li>
			<li><b>Keep things in their forum.</b> Help/Suggestions is <i>not</i> for your hack!</li>
		</ol>
		<br>As for the punishments:
		<ol>
			<li>A warning.
			<li>A short ban to drive the point home.
			<li>Permanent ban.
		</ol>
		<br>These punishments are a guideline and may be disregarded entirely for particularly egregious screwups.
		<br>
		<br>We're often pretty relaxed, but constantly breaking the rules will get you banned fast.
		<br>
		<br>If you have any questions, feel free to ask <a href='memberlist.php?pow=3'>one of the admins</a> for help.	
		<!--
		<center><img src='http://i55.photobucket.com/albums/g138/shalpp/1262546103597.jpg' title='NO. FUN. ALLOWED.'></center>
		-->
	");

	
	$faq	.= faqformat("aboot", "About Jul", "
		Jul itself is a community made up of people who predominantly like to just hang around friendlies and talk about whatever, though we also like games and occasionally anime/other things.
	");

	$faq	.= faqformat("newbies", "I'm new here. Where should I start?", "
		Always, by reading the rules... but since you're here, it's <i>probably</i> a safe bet that you've already done that. (If you haven't, <i>now is a great time.</i>)
		<br>
		<br>Once you've done that, <a href='register.php'>sign up for an account</a> (or <a href='login.php'>log in</a> if you've already made one). It's simple and very easy to do. After you're registered, you're more than welcome to just <a href='newthread.php?id=1'>jump in and say hi</a>. We're friendly people and won't bite (usually). Let us know about yourself, how you found us, or whatever's on your mind &mdash; or just jump in and start contributing to discussions.
	");

/*
	$faq	.= faqformat("n00b", "I have this <img src='http://xkeeper.net/img/noobsticker2-4.png' alt='n00b' title='TKEP regulars know this one' align='absmiddle' style='margin-top: -4px; margin-bottom: -4px;'> sticker on my post. What's up with that?", "
		The n00b sticker is our way of telling you that your post was pretty awful. Usually it's for one of the following reasons:
		<ol>
			<li>Complete disregard for our rules. If you show that you really can't even be bothered to read the small number of rules we have here, you're going to wear your welcome out <em>very</em> fast.</li>
			<li>Flagrant lack of basic knowledge. For example, if there's a sticky saying 'don't make a new thread for this' and you make a new thread for it, that's a big sign that you don't read the rules.</li>
			<li>Using dumb memes or bandwagoning. Everybody loves a laugh every now and then. Nobody loves it being rammed down their throat every five seconds.</li>
			<li>Terrible spelling or grammar. This is beyond the occasional misspelling (even the best of us make mistakes), but if you make a post loaded with \"Your a looser\", well...</li>
			<li>Your post is just mind-bogglingly terrible or groan-worthy.</li>
		</ol>
		The n00b sticker is something of a mark of shame. Usually it's an early warning indicator before we start taking issues with your actions on a broader scale, so if you see them, you should probably shape up. Note, however, that they can just as similarly be used as a joke.
		<br>
		<br><strong>Remember:</strong> The fastest way to get yourself stamped is to make a big deal out of it.
	");
*/

	$faq	.= faqformat("halp", "I've got a question and I need some help, or I found a bug somewhere.", "
		<a href='forum.php?id=39'>Post it in the forum here</a>, or alternatively just message the <a href='sendprivate.php?userid=1'>main administrator</a>. If it's a security bug in the code, we <i>really</i> recommend the latter.
	");

	$faq	.= faqformat("band", "I've been banned. Now what?", "
		You can try checking your title (under your username in your posts) to find out the reason and when it expires. If there's no expiration, it's probably <i>permanent</i>. If you're post due for unbanning, <a href='sendprivate.php?userid=1'>let an admin know</a> and they'll take care of it.
		<br>
		<br>On the other hand, if it's permanent, you can always try to show us you've changed and request a <i>second chance</i>... but any further antics after that will usually get your account <b>deleted</b>.
	");

	$faq	.= faqformat("cantpass", "I've lost/forgotten my password. Now what?", "
		The best thing you can do is to <a href='profile.php?id=1'>contact Xkeeper directly</a>. He can help you get it fixed.
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



/*
	print "<br>
	$tblstart
	<tr>$tccell1l>
			<b>What is this forum all about?</b>
			<br>Gaming, the internet in general, emulation, and rarely, ROM hacking. Though SM64 hacking is rather popular, considering.
			<br>

			<br><b>Okay, I'm new here. Where should I start?</b>
			<br>First off, read the rules before. They're not that long, and not that hard to follow; it'll make your life here a lot easier.
			<br>Next, feel free to <a href='newthread.php?id=1'>drop by and say hello</a>. Tell us about yourself, how you found out about us, or anything -- or just jump in and start posting.
			<br>It's up to you.
			<br>


			<br><b>What about the rules?</b>
			<br>Honestly, we follow <a href='http://forums.sonicretro.org/index.php?showtopic=11220'>Sonic Retro's ruleset</a> pretty closely, so read up there. Some things aren't relevant to here, though.
			<br>The gist of it:
			<ol>
				<li>Don't try talking in anything other than well-written English. If you are posting in another language, include an English translation, even if by machine. 'lulz-speak' is not tolerated here.
				<li>Don't be a dick. Nobody likes dicks. This includes posting just to complain about something forum-related, especially temporary.
				<li>Don't bump old (more than a month or two) threads without a decent reason.
				<li>Don't post blank, repeated, or completely off-topic replies.
				<li>Don't backseat mod. We're the staff, you aren't.
				<li>NSFW content must be <b>linked</b> and <b>tagged as such</b>. Not doing so is an instant permanent ban. (It's best to just not post it)
				<li>Admins are the final rules. If we tell you to do something, do it. <b>No exceptions.</b>
			</ol>As for the punishments:
			<ol>
				<li>Subtle warning.
				<li>More obvious warning, usually via PM.
				<li>Ban.
			</ol>We're pretty leinient, but we have limits.
			<br>

			
			<br><b>Something isn't working right.</b>
			<br><a href='forum.php?id=39'>Great, let us know.</a> We <i>love</i> fixing bugs.
			<br>

			<br><b>I've been banned!</b>
			<br>You probably did something against the rules and pissed off the staff. Check your title, it usually includes information as to why.
			<br>

			<br><b>No, I want to be <i>unbanned!</i></b>
			<br>Great. First of all, <a href='sendprivate.php?userid=1'>let us know</a>. If you can prove that you've learned your lesson, we'll give you another chance.
			<br>If you decide to evade your ban by reregistering, we will IP ban you and you will be prohibited from viewing this site, even via proxies. If you're stupid enough to try requesting unbanning again, <a href='http://xkeeper.shacknet.nu:5/docs/temp/lulz/megamoron.php'>well...</a>
			<br>

			<br><b>I'm g0nn4 h4x0rz ur 4um</b>
			<br>Sure you are. We've dealt with little script kiddies like you, and we know pretty much how you work. You might find one exploit somewhere, but we take daily backups and are sure to patch up the holes as soon as we find them.
			<br>

			<br><b>I have a question that you didn't answer (enough).</b>
			<br><a href='forum.php?id=39'>Let us know</a>.
			<br>

			<br><b>General Disclaimer</b> (i.e., Legal Crap)
			<br>The site does not own and cannot be held responsible for statements made by members on the forum. This site is offered as-is to the user. Any statements made on the board may be altered or removed at the discretion of the staff.
			<br>We do not sell member information like e-mail addresses or passwords to any third party. Data entered into user profiles is completely optional and may be left out at the user's discretion; however, doing so may complicate matters such as account recovery.
		</td></tr>
	$tblend
			";
*/
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