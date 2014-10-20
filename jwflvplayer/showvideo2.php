<?php
session_start();

require_once ('../include/dbc.php');
require_once '../include/common.funcs.php';

// declare global variables
$videotitle = "";
$videosource = $_GET['video']; // 这里用$_GET[]处理url传来的参数
//$videosource = "";
$videothumb = "";
$videoauthor = "";
$videodescription = "";
$videoaddedtime = "";
$video_kind = "";
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="<?php echo $videotitle; ?>, First Time, How To, Howto, video, help, Money, youtube, video website, video sharing" name="Keywords" />
<meta content="Here's is the place to  share your experience and lessons with other firsttimers from all over world to get things done by oneself!" name="Description" />
<meta name="Distribution" content="Global" />
<meta name="Author" content="Ye Henry Tian" />
<meta name="Robots" content="index,follow" />
<link rel="stylesheet" href="../images/ftstyle2.0.css" type="text/css" />
<meta content="jaolb+4U3+k7xWefD1IT+pPv3Nevk/TJsQW8ZV3uXBI=" name="verify-v1" />
<link href="../firsttimer-ico.png" rel="shortcut icon" />
<title>FirstTimer - <?php echo $videotitle; ?></title>
</head>

<body>
<!-- wrap starts here -->
<div id="wrap">
	<?php echo (emitpageheader()); ?>
				
	<!-- content-wrap starts here -->
	<div id="content-wrap">
       <div id="content">      	
		<div id="sidebar">
            <div class="sidebox">
               <?php echo (emituseraccountbox($_SESSION['user'])); ?>
			</div>
        
            <div class="sidebox">			
				<h1>Short About</h1>				
				<p>
				<a href="../jwflvplayer/showvideo2.php?video=<?php echo ($_GET['video']);?>" target="_parent" title="<?php echo $videotitle;?> "><img src="<?php echo $videothumb;?>" width="150" height="100" alt="<?php echo $videothumb;?>"  class="float-left" /></a></p>
                <br /><br /><br /><br /><br /><br />
                <p><strong>Author:</strong> <a href="../htmltemplates/useraccount2.php?accountname=<?php echo $videoauthor; ?>" target="_parent" 
                title="See <?php echo $videoauthor;?>'s profile"><?php echo $videoauthor; ?></a><br />
                <strong>Date added:</strong> <?php echo $videoaddedtime; ?><br /></p>
                <p><strong>Video category:</strong> <a href="../category/category.php?cat=<?php echo $video_kind; ?>"><?php echo populatevidoecategoryname($video_kind); ?> </a></p>
                <p><strong>Video description:</strong></p>
                <p style="height: 100px; width:90%; overflow: auto; overflow-x: hidden;">
                <?php echo $videodescription; ?>
                </p>  								
						
			</div>
            
            <div class="sidebox">
				<?php echo(emitgooglereview($video_id)) ?>
			</div>
            
            <div class="sidebox">			
				<h1 class="clear">Sidebar Menu</h1>
				<ul class="sidemenu">
					<li><a href="#Video">Video</a></li>
					<li><a href="#VideoRating">Video Rating</a></li>
                    <li><a href="#RelatedVideoList">Related Video List</a></li>
                    <li><a href="#VideoComments">Video Comments</a></li>
                    <li><a href="#AddComments">Add Video Comments</a></li>				
				</ul>				
			</div>            
			
            <div class="sidebox">			
				<?php echo (emitstartnow()); ?>					
		    </div>			
            
            <div class="sidebox">
                <?php echo (emitsearchbox(true)); ?>
            </div>
            
			<div class="sidebox">			
				<h1>Advertisements</h1>
				<div align="center">
                  <script type="text/javascript"><!--
                   google_ad_client = "pub-6635598879568444";
                   /* firsttimer 160x600, created 12/17/08 */
                   google_ad_slot = "1050254913";
                   google_ad_width = 160;
                   google_ad_height = 600;
                   //-->
                  </script>
                  <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
                </div>				
			</div>
					
		</div>
		<div id="main">
           <?php echo emitrecbrowsers(); ?>
           
           <a name="Video"></a>
           <!--
           <div align="center" style="width: 702px; height: 450px; background-color: #000000; border: 1px solid #CCCCCC; -moz-border-radius: 20px">
                <div id="preview" align="center">
	            <script type="text/javascript" src="swfobject.js"></script>
                <script type="text/javascript">
                var s1 = new SWFObject("player-viral.swf","play","680","400","9","#ffffff");
                s1.addParam('allowfullscreen','true');
                s1.addParam('allowscriptaccess','always');
	            s1.addParam('allownetworking','all');
                s1.addParam('wmode','opaque');
	            s1.addVariable('file','');
				s1.addVariable('config','configone.xml');
				s1.addVariable('sharing.link', window.location);
                s1.write("preview");
                </script>
                </div>
             </div> -->
             
             <div align="center" style="width: 702px; height: 450px; background-color: #000000; border: 1px solid #CCCCCC; -moz-border-radius: 20px">
			    <h1>Title: <?php echo $videotitle; ?></h1>
                <div id="preview" align="center">
                <object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='680' height='400' id='player1' name='player1'>
                <param name='movie' value='player-viral.swf'>
                <param name='allowfullscreen' value='true'>
                <param name='allowscriptaccess' value='always'>
                <param name='allownetworking' value='all'>
                <param name='wmode' value='opaque'>
                <param name='flashvars' value='file=<?php echo $videosource;?>&config=configone.xml'>
                <embed id='player1'
                       name='player1'
                       src='player-viral.swf'
                       width='680'
                       height='400'
                       allowscriptaccess='always'
                       allowfullscreen='true'
                       allownetworking='all'
                       wmode='opaque'
                       sharing.link="<script>window.location</script>"
                       flashvars="file=<?php echo $videosource;?>&config=configone.xml"/>
               </object>
               </div>
             </div>            
             
             <a name="VideoRating"></a>
             <table style="width:100%;">
             <tr>
             <td class="first" width="5px">
             <h3 style="margin:0">Ratings:</h3>
             </td>
             <td align="left" width="120px">
             <ul class="star-rating">
             <li class='current-rating' title='Currently 3.5/5 Stars' style='width:50px;'>Currently 2.5/5 Stars.</li> 
             <li><a href="#" title="Rate this 1 star out of 5" class="one-star">It deserves 1 star</a></li>
             <li><a href="#" title="Rate this 2 stars out of 5" class="two-stars">It deserves 2 stars</a></li>
             <li><a href="#" title="Rate this 3 stars out of 5" class="three-stars">It deserves 3 stars</a></li>
             <li><a href="#" title="Rate this 4 stars out of 5" class="four-stars">It deserves 4 stars</a></li>
             <li><a href="#" title="Rate this 5 stars out of 5" class="five-stars">It deserves 5 stars!</a></li>
             </ul>
             </td>
             <td align="left" width="100px">
             <div align="left" style="margin-top:5px;"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="yehenrytian">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
             </td>
             <td align="left" width="110px">
             <a title="Post to Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="small-count"></a>
<script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>
             </td>
             <td align="left" width="100px">
             <a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
             </td>
             
             <td align="right">
             <h3>Views: <?php echo $views_count; ?></h3>
             </td>
             </tr>
             </table>

                         
			 <a name="RelatedVideoList"></a>
			 <h1>Related video list</h1>
             <div style="overflow:auto; overflow-y: hidden; height:180px; width:auto; border:1px solid #CCC">
             <?php echo (populatecategoryvideolist("date_added", 100, $video_kind)); ?>               
             </div>
             
             <a name="VideoComments"></a>
             <h1>Video comments</h1>
             <script language="JavaScript" type="text/javascript">
             document.write("<iframe style=\"height:320px; width: 680px;\" src=\"../htmltemplates/commentreload.php?videoid=<?php echo $video_id ?>\" id='videocommentlist' scrolling=\"auto\" frameborder=\"no\" align=\"center\" ></iframe>" )</script>
                
             <a name="AddComments"></a>
             <script type="text/javascript" src="../include/ajax.js"></script>
             <a href="javascript:ajaxpage('../htmltemplates/commentreload.php?videoid=<?php echo $video_id ?>', 'videocomments');"><h3>Add comments to this video</h3></a>
<?php
if (isset($_SESSION['user']))
   {
?>
<script language="JavaScript" type="text/javascript">
document.write("<iframe style=\"height:220px; width: 680px;\" src=\"../htmltemplates/addcomment2.php?videoid=<?php echo $video_id ?>\" id='addcomment' scrolling=\"auto\" frameborder=\"no\" align=\"left\" ></iframe>" )</script>
<?php
   }
else
   {
?>
<script language="JavaScript" type="text/javascript">
document.write("<iframe style=\"height:50px; width: 680px;\" src=\"../htmltemplates/addcomment2.php?videoid=<?php echo $video_id ?>\" id='addcomment' scrolling=\"auto\" frameborder=\"no\" align=\"left\" ></iframe>" )</script>
<?php 
   } 
?>

             <h3>Google Ads</h3>       
		     <div align="center">
             <script type="text/javascript">
             google_ad_client = "pub-6635598879568444";
             /* 728x90footer, created 1/15/09 */
             google_ad_slot = "7642261806";
             google_ad_width = 728;
             google_ad_height = 90;
             </script>
             <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
             </div>	
		</div>					
		
	<!-- content-wrap ends here -->		
	</div></div>

   <?php echo (emitpagefooter()); ?>
	
<!-- wrap ends here -->
</div>



</body>
</html>
