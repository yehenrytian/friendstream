<?php
session_start();

require_once ('../include/dbc.php');
require_once '../include/common.funcs.php';

// declare global variables
$videotitle = "";
$videosource = "";
$videothumb = "";
$videoauthor = "";
$videodescription = "";
$videoaddedtime = "";
$video_count = 0;
$video_list = "";
//$condition = $_GET['cond']; // 这里用$_GET[]处理url传来的视频order by的参数
//$col = $_GET['col']; // 这里用$_GET[]处理url传来的video list column的个数
//$hide = $_GET['hide']; // 这里用$_GET[]处理url传来的哪边的scrolling不显示

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="First Time, How To, Howto, video, help, Money, youtube, video website, video sharing" name="Keywords" />
<meta content="Here's is the place to  share your experience and lessons with other firsttimers from all over world to get things done by oneself!" name="Description" />
<meta name="Distribution" content="Global" />
<meta name="Author" content="Ye Henry Tian" />
<meta name="Robots" content="index, follow" />
<link rel="stylesheet" href="../images/ftstyle2.0.css" type="text/css" />
<meta content="jaolb+4U3+k7xWefD1IT+pPv3Nevk/TJsQW8ZV3uXBI=" name="verify-v1" />
<link href="../firsttimer-ico.png" rel="shortcut icon" />
<title>FirstTimer - FirstTimer Videos</title>
</head>

<body>
<!-- wrap starts here -->
<div id="wrap">
	<?php echo (emitpageheader()); ?>
    
    <div id="content-wrap">
       <div id="content">
       <div class="post">			
		   <a name="TemplateInfo"></a>	
		   <h1>Firsttimer Video Info</h1>
	       <p>Here are the FirstTimer videos (some of the videos are just for testing purpose, and are not &quot;REAL FirstTimer Videos&quot;) 
           The website is still under construction! More videos are coming soon...<strong>Stay tune! </strong>
           (This player requires <a href="http://get.adobe.com/flashplayer/" target="_blank" title="Adobe Flash Plyer Download">Adobe Flash</a>)</p>
       </div>
		
	<!-- content-wrap ends here -->		
	</div></div>
    
	 <div id="ftfooter" align="center">
      <div align="center" style="width: 900px; height: 310px; background-color: #000000; border: 1px solid #CCCCCC; -moz-border-radius: 20px">
      <h1 align="center" style="font-size: 25px; border-bottom: 1px solid #f2f2f2;">Video Samples</h1>
      <div id="preview" align="center"> 
	   <!--
	   <script type="text/javascript" src="swfobject.js"></script>
       <script type='text/javascript'>
       var s1 = new SWFObject('player-viral.swf','player',"875","240",'9',"#ffffff");
       s1.addParam("allowfullscreen","true");
       s1.addParam("allowscriptaccess","always");
	   s1.addParam("allownetworking","all");
       s1.addParam("wmode","opaque");
	   s1.addVariable('config','config.xml');
	   //s1.addParam("flashvars", "config=config.xml");
       s1.write("preview");
       </script>-->
       <object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='875' height='240' id='player1' name='player1'>
                <param name='movie' value='player-viral.swf'>
                <param name='allowfullscreen' value='true'>
                <param name='allowscriptaccess' value='always'>
                <param name='allownetworking' value='all'>
                <param name='wmode' value='opaque'>
                <param name='flashvars' value='config=config.xml'>
                <embed id='player1'
                       name='player1'
                       src='player-viral.swf'
                       width='875'
                       height='240'
                       allowscriptaccess='always'
                       allowfullscreen='true'
                       allownetworking='all'
                       wmode='opaque'
                       flashvars="config=config.xml"/>
      </object>       
      </div>
      </div>           
     
    </div>   
	
    			
<!-- content-wrap starts here -->
	<div id="content-wrap">
       <div id="content">		
		<div id="sidebar" style="width:19%">
            <div class="sidebox">			
				<?php echo(emitcategories()) ?>
			</div>	
            
            <div class="sidebox">
               <?php echo (emituseraccountbox($_SESSION['user'])); ?>
	        </div>
             
            <div class="sidebox">
              <?php echo (emitstartnow(163)); ?>
	        </div>
             
			<div class="sidebox">
                <?php echo (emitsearchbox(true)); ?>
			</div>
            			
		</div>
        
		<div id="main" style="width:800px">
         <div align="center" style="width: 800px; height: auto; background-color: #CCCCCC; -moz-border-radius: 15px">
         <h1><img src="../images/icons/Flickr.png" style="background-color:#CCC; width:25px; height:25px; border:0; margin:0" alt="fr" />
         Most Recent Video Samples (total video count: <?php $video_list = populatevideolist("date_added", 6, ""); echo $video_count; ?>)
         <img src="../images/icons/Flickr.png" style="background-color:#CCC; width:25px; height:25px; border:0; margin:0" alt="fr" /></h1>
         </div>
         <div style="overflow:auto; overflow-x: hidden; height:500px; width: auto;">
           <?php echo ($video_list); ?>
         </div>
        
         <?php echo emitrecbrowsers(); ?>
        		
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
			
		<br />										
		</div>					
		
	<!-- content-wrap ends here -->		
	</div></div>

   <?php echo (emitpagefooter()); ?>   
	
<!-- wrap ends here -->
</div>



</body>
</html>
