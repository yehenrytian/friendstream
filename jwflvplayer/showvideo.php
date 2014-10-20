<?php
session_start();

require_once ('../include/dbc.php');
require_once '../include/common.inc.php';

// declare global variables
$videotitle = "";
$videosource = $_GET['video']; // 这里用$_GET[]处理url传来的参数
//$videosource = "";
$videothumb = "";
$videoauthor = "";
$videodescription = "";
$videoaddedtime = "";
$views_count = 0;
$video_id = 0;
$videocommentscount = 0;

// no argumment specified
if ($videosource == NULL)
   header("Location: ../404.html");

// call populate video info
if ($videosource)
   {
   populatevideoinfo(true);
   }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="First Time, How To, Howto, video, help, Money, youtube, video website, video sharing" name="Keywords" />
<meta content="FirstTimer Video" name="Description" />
<meta content="jaolb+4U3+k7xWefD1IT+pPv3Nevk/TJsQW8ZV3uXBI=" name="verify-v1" />
<link href="../firsttimer-ico.png" rel="shortcut icon" />
<title>FirstTimer Video - <?php echo $videotitle; ?></title>
<link href="../ftstyle.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="page">
<script type="text/javascript">
document.write("<iframe src=\"../htmltemplates/ftheader.php\" name=\"ftheader\" scrolling=\"no\" frameborder=\"no\" align=\"center\" height = \"200px\" width = \"970px\" ></iframe>")
</script>

<br /><br />
<div align="center" style="width: 970px; height: 900px; background-color: #999999; border: 1px solid #CCCCCC; -moz-border-radius: 20px">
<p></p>
<table cellspacing="3" cellpadding="0" border="0" bgcolor="#999999" width="auto" height="auto">
<tbody>
<tr>
<td>
<div align="center" style="width: 482px; height: 440px; background-color: #000000; border: 1px solid #CCCCCC; -moz-border-radius: 20px">
<h2 align="center" style="font-size: 18px;">Title: <?php echo $videotitle; ?></h2>
	<div id="preview" align="left">
	<script type="text/javascript" src="swfobject.js"></script>
    <script type="text/javascript">
    var s1 = new SWFObject("player-viral.swf","player","480","400","9","ffffff");
    s1.addParam("allowfullscreen","true");
    s1.addParam("allowscriptaccess","always");
	s1.addParam("allownetworking","all");
    s1.addParam("wmode","opaque");
	s1.addParam("flashvars", "file=<?php echo $videosource;?>&config=configsingle.xml");
    s1.write("preview");
    </script>
    </div>
</div>
</td>

<td>
<table cellspacing="0" cellpadding="0" border="0" bgcolor="#999999" width="100%" height="100%">
<tbody>
<tr>
<td colspan="2" valign="top" style="font-size: 10px; font-weight: bold; color: #333333;">Video thumb:<br />
<img src="<?php echo $videothumb;?>" width="150" height="120" border="0"/>
<br/>
Author: <a id="ftlink" href="../htmltemplates/useraccount.php?accountname=<?php echo $videoauthor; ?>" target="_parent" title="See <?php echo $videoauthor;?>'s profile"><?php echo $videoauthor; ?></a><br />Date added: <?php echo $videoaddedtime; ?>
</td>
</tr>
<tr>
<td valign="top" style="width: 120px; font-size: 10px; font-weight: bold; color: #333333;">
Video description: 
</td>
<td style="font-size: 10px; font-weight: bold; color: #333333;">
<div style="width: 200px; height: 55px; overflow: auto">
<?php echo $videodescription; ?> 
</div>
</td>
</tr>
<tr>
<td valign="bottom" colspan="2">
<div style="color: #0066CC; font-size: 14px; font-weight:bold">Most recent video samples</div>
<script language="JavaScript" type=text/javascript>
document.write("<iframe src=\"../htmltemplates/videolist.php?cond=date_added&col=100&hide=Y\" name=\"videolist\" scrolling=\"auto\" frameborder=\"no\" align=\"center\" height = \"170px\" width = \"468px\"></iframe>" )
</script>
</td>
</tr>
</tbody>
</table>
</td>
</tr>

<tr>
<td>
<table cellspacing="0" cellpadding="0" border="0" bgcolor="#999999" width="100%">
<tbody>
<tr>
<td style="width: 10px;">
<div style="font-size: 12px; font-weight: bold; color: #333333;">Rate:</div>
</td>
<td>
<ul class="star-rating">
<li class='current-rating' title='Currently 3.5/5 Stars' style='width:51px;'>Currently 2.5/5 Stars.</li> 
<li><a href="#" title="Rate this 1 star out of 5" class="one-star">It deserves 1 star</a></li>
<li><a href="#" title="Rate this 2 stars out of 5" class="two-stars">It deserves 2 stars</a></li>
<li><a href="#" title="Rate this 3 stars out of 5" class="three-stars">It deserves 3 stars</a></li>
<li><a href="#" title="Rate this 4 stars out of 5" class="four-stars">It deserves 4 stars</a></li>
<li><a href="#" title="Rate this 5 stars out of 5" class="five-stars">It deserves 5 stars!</a></li>
</ul>
</td>
<td>
<div align="right" style="font-size: 12px; font-weight: bold; color: #333333;">Views: <?php echo $views_count; ?></div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>

<tr>
<td>
<div style="color: #0066CC; font-size: 14px; font-weight:bold;">Video Comments:</div>
<script language="JavaScript" type=text/javascript>
document.write("<iframe style=\"height:156px; width: 480px;\" src=\"../htmltemplates/comments.php?videoid=<?php echo $video_id ?>\" id='videocommentlist' scrolling=\"auto\" frameborder=\"no\" align=\"center\" ></iframe>" )
</script>
</td>
<td>
<div align="center">
<span style="color: #0066CC; font-size: 14px; font-weight:bold;">Advertisements:</span>
<br />
<script type="text/javascript"><!--
google_ad_client = "pub-6635598879568444";
/* 200x200firsttimer, created 1/17/09 */
google_ad_slot = "1941255369";
google_ad_width = 200;
google_ad_height = 200;
//-->
</script> 
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
</td>
</tr>

<tr>
<td>
<div style="width: 480px; height: 170px; background-color: #999999; border: 1px solid #CCCCCC; -moz-border-radius: 14px">
<p></p><p></p>
<script language="JavaScript" type=text/javascript>
document.write("<iframe style=\"height:auto; width: 480px;\" src=\"../htmltemplates/addcomment.php?videoid=<?php echo $video_id ?>\" id='addcomment' scrolling=\"auto\" frameborder=\"no\" align=\"center\" ></iframe>" )
</script>
</div>
</td>
<?php
if (!isset($_SESSION['user']))
{
?>
<td><input type="hidden" id="postcomment" disabled="disabled"/></td>
<?php
}
else
{
?>
<td><input type="hidden" id="postcomment" /></td>
<?php
}
?>

</tr>
</tbody>
</table>
</div>
<br />

<div align="center">
<script type="text/javascript">
google_ad_client = "pub-6635598879568444";
/* 728x90footer, created 1/15/09 */
google_ad_slot = "7642261806";
google_ad_width = 728;
google_ad_height = 90;
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
<script type="text/javascript">
document.write("<iframe src=\"../htmltemplates/ftfooter.html\" name=\"ftfooter\" scrolling=\"no\" frameborder=\"no\" height = \"auto\" width = \"100%\" ></iframe>")
</script>
</div>
</body>
</html>

