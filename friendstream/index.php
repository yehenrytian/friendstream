<?php
require_once ('../include/friendstream.php');
//require_once ('googleplus-access.php');
ini_set('session.gc_maxlifetime', 3600);
session_cache_limiter ('private, must-revalidate');
session_cache_expire(60); // in minutes 
session_start();

// handle Google Buzz redirect issue
$req = explode('/', $_SERVER['REQUEST_URI']);
if ($req[1] == 'friendstream') header ("Location: http://friendstream.ca");

/* Create facebook connection */
    $config['baseurl']  =  'http://friendstream.ca';
	$config['baseurllogout']  =  '../include/twitterapi/clearsession.php?facebook=clear';
	//unset($_SESSION['facebook']);

	// Create our Application instance.
    if (!isset($_SESSION['facebook'])) {
	$facebook = new Facebook(array(
      'appId'  => $fbconfig['appid'],
      'secret' => $fbconfig['secret'],
      'cookie' => true,
    ));
	}
	else{
	   $facebook = $_SESSION['facebook'];
	}
	
	 // We may or may not have this data based on a $_GET or $_COOKIE based session.
    // If we get a session here, it means we found a correctly signed session using
    // the Application Secret only Facebook and the Application know. We dont know
    // if it is still valid until we make an API call using the session. A session
    // can become invalid if it has already expired (should not be getting the
    // session back in this case) or if the user logged out of Facebook.
	$user = NULL;
	
    if ($facebook->getAccessToken())
	   $user = $facebook->getUser();
	
	//print_r($facebook->getAccessToken());

    $fbme = NULL;
    // Session based graph API call.
    if ($user) {
      try {
        //$uid = $facebook->getUser();
        $fbme = $facebook->api('/me');
      } catch (FacebookApiException $e) {
          //dump($e);
      }
    }
 
    //if user is logged in and session is valid.
    if ($fbme){
	    if (!isset($_SESSION['facebook']))	
           $_SESSION['facebook'] = $facebook;		
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <title>FriendStream - Keep you updated of what is happening in your social networks</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="Friendsteam is where you can get all your social network updates in one page" />
    <meta name="keywords" content="twitter, facebook, google buzz, web 2.0, smartphone, social network, SNA, email" />
    <meta name="author" content="Ye Henry Tian" />
    <meta name="distribution" content="global" />
    <meta name="robots" content="follow, all" />
    <meta name="language" content="en" />
    <meta name="revisit-after" content="2 days" />
    <meta property="wb:webmaster" content="309eb8104fb27ab2" />
    <meta content="jaolb+4U3+k7xWefD1IT+pPv3Nevk/TJsQW8ZV3uXBI=" name="verify-v1" />
    <link rel="shortcut icon" href="../images/fsicon2.png" />
    <link href="fscss.css" rel="stylesheet" type="text/css" />
</head>

<body>

<!--
<script type="text/javascript" src="//www.hellobar.com/hellobar.js"></script>
<script type="text/javascript">
//new HelloBar(1,9126);
</script>
<noscript>Help The Victims of the M9.0 Earthquake in Japan by Spreading Awareness and Aid. Visit http://goo.gl/wjZQz to donate.
</noscript>
-->


<div id="fb-root"></div>
<script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({appId: '<?=$fbconfig['appid']?>', status: true, cookie: true, xfbml: true});

                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
                    login();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
                    logout();
                });
				
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js&xfbml=1';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());

            function login(){
                document.location.href = "<?=$config['baseurl']?>";
            }
            function logout(){
                document.location.href = "<?=$config['baseurllogout']?>";
            }
</script>


<div id="friendstreamwrapper">
<div id="wrapper">

<div id="head">
    <h1><a href="http://friendstream.ca"></a></h1>
</div>

<div id="search" align="right">
<form action="http://www.google.ca/cse" id="cse-search-box" target="_blank">
  <div>
    <input type="hidden" name="cx" value="partner-pub-6635598879568444:c8xv0514j98" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" size="30" />
    <input type="image" style="width:25px; height:25px;" name="sa" value="Search" src="../images/icon_search.png"/>
  </div>
</form>
<script type="text/javascript" src="http://www.google.ca/cse/brand?form=cse-search-box&amp;lang=en"></script>
</div>


<div id="columns">
<ul id="column2" class="column">
  <li class="widget color-blue" >
   <div class="widget-head">
    <h3>Twitter Tweets</h3>
   </div>
   <div class="widget-content">
    <?php
	$needlogin = false;
    $request_link = '';
	/* If access tokens are not available redirect to connect page. */
    if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
        $needlogin = true;

    // get the authorize connection link
    if ($consumer_key === '' || $consumer_secret === '') {
       echo 'You need a consumer key and secret to test the sample code. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a>';
       exit;
    }
    /*
    Create a new TwitterOAuth object, and then get a request token. The request token will be used to build the link the user will use to authorize the application.
    You should probably use a try/catch here to handle errors gracefully
    */
    $to = new TwitterOAuth($consumer_key, $consumer_secret);
	$tok = $to->getRequestToken(OAUTH_CALLBACK);
	
	//$_SESSION['twitterOAuth'] = $to;

    /*
    Save tokens for later  - we need these on the callback page to ask for the access tokens
    */
    $_SESSION['oauth_token'] = $token = $tok['oauth_token'];
    $_SESSION['oauth_token_secret'] = $tok['oauth_token_secret'];
	
	/* If last connection failed don't display authorization link. */
    switch ($to->http_code) {
      case 200:
        /* Build authorize URL and redirect user to Twitter. */
        $request_link = $to->getAuthorizeURL($tok);
        break;
      default:
        /* Show notification if something went wrong. */
        echo 'Could not connect to Twitter. Refresh the page or try again later.';
     }	
}
else {
     /* Get user access tokens out of the session. */
     $access_token = $_SESSION['access_token'];

     /* Create a TwitterOauth object with consumer/user tokens. */
	 if (!isset($_SESSION['twitter']))
	    {
        $twitter = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
	    $_SESSION['twitter'] = $twitter;
		}
     }

	if ($needlogin)
	   {
	   echo '<div class="snaoauth"><span class="notice">Sign in via Twitter to see your recent tweets from people you follow.</span>';
	   echo '<p><a href="'.$request_link.'" title="Sign in with Twitter"><img style="padding-top:5px" src="../images/darker.png" alt="Sign in with Twitter"/></a></p></div>';
	   }
	else
	   {
	   $tweetsList = get_tweets();
	   echo '<div class="snaoauth"><p><img src="../images/t_small-c.png" alt="Sign out of Twitter"/><a class="button blue" href="../include/twitterapi/clearsession.php?twitter=clear">Sign out of Twitter</a></p></div>';
	   
	   echo '<div class="snaoauth"><p>';
       echo '<img src="../images/clock.png" alt="clock" width="25px" height="25px"/>
	         <select name="twitterautorefresh" onchange="autoRefresh(this.name, this.value)">
	          <option value="0">no auto refresh</option>
              <option value="3">every 3 minutes</option>
              <option value="5">every 5 minutes</option>
              <option value="10">every 10 minutes</option>
              <option value="15">every 15 minutes</option>
              <option value="20">every 20 minutes</option>
             </select><span class="notice">(auto refresh)</span>';            
       echo '</p></div>';
	   
	   echo '<div class="snaoauth"><p><img src="../images/adept_update.png" alt="refresh" width="25px" height="25px"/><a id="twitterrefresh" href="javascript:void(0);" onclick="getAjaxUpdates(\'get_tweets\', \'\', \'twitterlist\')" title="refresh tweets">refresh tweets</a></p></div>';
	   echo '<div id="refreshTweets" class="loading"></div>';
	   echo ($tweetsList);
	   }
	?> 
   </div>
   </li>
</ul>
<script type="text/javascript">
setInterval("document.getElementById('twitterrefresh').onclick();", 1200000);
</script>

<ul id="column2" class="column">
  <li class="widget color-fbblue">  
   <div class="widget-head">
     <h3>Facebook Updates</h3>
   </div>
   <div class="widget-content">
    <?php if (!$fbme) { ?>
        <span class="notice" style="padding-bottom:5px">Login via Facebook to see your recent wall posts from your friends.</span>
    <?php } ?>
    <div class="snaoauth">
    <p style="padding-top:5px">
    <fb:login-button autologoutlink="true" perms="read_stream,user_status,friends_status,user_location,friends_location,status_update,publish_stream"></fb:login-button>
    </p></div>
   
    <?php 
	// emit updates list
	if ($fbme){ 
	   ?>
	   <!-- Data retrived from user profile are shown here -->
       <div class="snaoauth"><p>
       <img src="../images/clock.png" alt="clock" width="25px" height="25px"/>
	         <select name="fbautorefresh" onchange="autoRefresh(this.name, this.value)">
	          <option value="0">no auto refresh</option>
              <option value="3">every 3 minutes</option>
              <option value="5">every 5 minutes</option>
              <option value="10">every 10 minutes</option>
              <option value="15">every 15 minutes</option>
              <option value="20">every 20 minutes</option>
             </select><span class="notice">(auto refresh)</span>           
        </p></div>
       
       <div class="snaoauth"><p><img src="../images/adept_update.png" alt="refresh" width="25px" height="25px"/><a id="fbrefresh" href="javascript:void(0);" onclick="getAjaxUpdates('get_fbupdates', '', 'fblist')" title="refresh updates">refresh posts</a></p></div>
       <div id="refreshUpdates" class="loading"></div>
       <?php
	     echo (get_fbupdates());
	   } ?>
    </div>
    </li>
</ul>
<script type="text/javascript">
setInterval("document.getElementById('fbrefresh').onclick();", 600000);
</script>
        
<ul id="column2" class="column">
  <li class="widget color-green">  
   <div class="widget-head">
     <h3>Google Plus</h3>
   </div>
   <div class="widget-content">
<?php
if (!isset($_SESSION['gplusobj'])){
	echo '<div class="snaoauth"><span class="notice">Sign in Google+ to see your google plus activity stream.</span>';
	echo '<p><img src="../images/g-plus-icon-32x32.png" width="24" height="24" alt="google plus"/><a class="button blue" href="'.$authUrl.'">Sign in Google+</a></p></div>';
}
else{
  // iterate through feed data
  echo '<div class="snaoauth"><p><img src="../images/g-plus-icon-32x32.png" width="20" height="20" alt="google plus" /><a class="button blue" href="../include/twitterapi/clearsession.php?buzz=clear">Sign out of Google+</a></p></div>';
  
  echo '<div class="snaoauth"><p>';
  echo '<img src="../images/clock.png" alt="clock" width="25px" height="25px"/>
	         <select name="buzzautorefresh" onchange="autoRefresh(this.name, this.value)">
	          <option value="0">no auto refresh</option>
              <option value="3">every 3 minutes</option>
              <option value="5">every 5 minutes</option>
              <option value="10">every 10 minutes</option>
              <option value="15">every 15 minutes</option>
              <option value="20">every 20 minutes</option>
             </select><span class="notice">(auto refresh)</span>';            
  echo '</p></div>';
  
  echo '<div class="snaoauth"><p><img src="../images/adept_update.png" alt="refresh" width="25px" height="25px"/><a id="buzzrefresh" href="javascript:void(0);" onclick="getAjaxUpdates(\'get_pluses\', \'\', \'buzzlist\')" title="refresh pluses">refresh google plus</a></p></div>';
  echo '<div id="refreshBuzzs" class="loading"></div>';
  $plusList = get_pluses();
  echo ($plusList);

}
?>
   </div>
  </li>
 </ul>
<script type="text/javascript">
setInterval("document.getElementById('buzzrefresh').onclick();", 1200000);
</script>
        
<ul id="column2" class="column">
  <li class="widget color-red">  
    <div class="widget-head">
      <h3>新浪微博</h3>
    </div>
    <div class="widget-content">
    <?php	
	$weibologin = false;
    $aurl = '';
	/* If access tokens are not available redirect to connect page. */
    if ($_SESSION['weibostatus'] != 'verified') {
	   $weibologin = true;

    // get the authorize connection link
    if (!defined('WB_AKEY') || !defined('WB_SKEY')) {
       echo 'You need a consumer key and secret to test the sample code. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a>';
       exit;
    }
    
   //$o = new WeiboOAuth(WB_AKEY , WB_SKEY);
   $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

   //$callback = 'http://friendstream.ca/include/weibo/callback.php';
   //$keys = $o->getRequestToken($callback);
   //$_SESSION['keys'] = $keys;
   $aurl = $o->getAuthorizeURL( WB_CALLBACK_URL  );
    
	/* If last connection failed don't display authorization link. */
	/*
    switch ($o->http_code) {
      case 200:
        /* Build authorize URL and redirect user to Sina. */
        //$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $callback );
		//$aurl = $o->getAuthorizeURL( $callback  );

        //break;
      //default:
        /* Show notification if something went wrong. */
        //echo 'Could not connect to Sina Weibo. Refresh the page or try again later.';
     //}	*/
}
else {
	 if (!isset($_SESSION['weibo']))
	    {
        $weibo = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$_SESSION['weibo'] = $weibo;
		}
     //$me = $weibo->verify_credentials();
	 
	 if (!isset($_SESSION['emotions']))
        {
        $emotions = $_SESSION['weibo']->emotions();
		//print_r($emotions);
        foreach($emotions as $value) 
           { 
           $data[$value['phrase']] = $value['url'];  
           } 
        //write_to_file('emotions', $data); 
        $_SESSION['emotions'] = $data;
	    }
     }

	if ($weibologin)
	   {
	   echo '<div class="snaoauth"><span class="notice">通过您的新浪微薄帐号登录，查看最新微博更新。</span>';
	   echo '<p><a href="'.$aurl.'" title="登录新浪微博"><img style="padding-top:5px; width:151px; height:28px;" src="../images/weibologin.png" alt="用微博帐号登录"/></a></p></div>';
	   }
	else
	   {
	   $weiboList = get_weibos();
	   echo '<div class="snaoauth"><p><img src="../images/weibo48x48.png" alt="Sign out of Weibo" width="20px" height="20px"/><a class="button red" href="../include/twitterapi/clearsession.php?weibo=clear">退出新浪微博</a></p></div>';
	   
	   echo '<div class="snaoauth"><p>';
	   echo '<img src="../images/clock.png" alt="clock" width="25px" height="25px"/>
			 <select name="weiboautorefresh" onchange="autoRefresh(this.name, this.value)">
	          <option value="0">no auto refresh</option>
              <option value="3">every 3 minutes</option>
              <option value="5">every 5 minutes</option>
              <option value="10">every 10 minutes</option>
              <option value="15">every 15 minutes</option>
              <option value="20">every 20 minutes</option>
             </select><span class="notice">(auto refresh)</span>';           
	   echo '</p></div>';
	   
	   echo '<div class="snaoauth"><p><img src="../images/adept_update.png" alt="refresh" width="25px" height="25px"/><a id="weiborefresh" href="javascript:void(0);" onclick="getAjaxUpdates(\'get_weibos\', \'\', \'weibolist\')" title="refresh weibos">刷新微博</a></p></div>';
	   
	   echo '<div id="refreshWeibos" class="loading"></div>';
	   echo ($weiboList);
	   }
	?> 

    </div>
  </li> 
</ul>
<script type="text/javascript">
setInterval("document.getElementById('weiborefresh').onclick();", 1200000);
</script>

</div> <!--columns-->


<div id="columns"> <!--intro-->
<?php if ($fbme || isset($_SESSION['twitter']) || isset($_SESSION['gplusobj']) || isset($_SESSION['weibo'])) { 
         echo '<!--';
} 
?>
<ul id="column-footer" class="column">
  <li class="widget color-orange" id="intro">
   <div class="widget-head">
    <h3>Site Intro</h3>
   </div>
   <div class="widget-content">
   
   <div class="columnleft">
    <h2>Welcome to <span>Friendstream</span></h2>
    <br />
    <p style="font-size:13px;">Friendstream lets you view your social network (currently support Twitter, Facebook, Google Plus, 新浪微博) real-time recent "happenings" all in one page. You can also broadcast happenings just in one post and re-broadcasting happenings between different social networks just in one click.</p>
    <br />
    <h4><span>Features</span></h4>
    <ul class="features">
      <li>View your social network real-time "happenings" all in one page.</li>
      <li>Broadcast your status to your social networks just in one post.</li>
      <li>Re-broadcasting happenings between different social networks(StreamIt) just in one click.</li>
      <li>Use OAuth authentication for soical network website access (secure and fast).</li>
      <li>No need to signup or register, use friendstream directly through your soical network accounts.</li>
      <li>It is FREE after all!!</li>
    </ul>
</div>

<div class="columnleft">
  <h3>Recent <span>Updates</span></h3>
  <br />
  <div style="overflow:auto; overflow-x: auto; height:380px; width:100%; -moz-border-radius: 15px;">
    <ul class="updates">
      <li>Updated to use Twitter REST v1.1 API<span> July 16, 2013 </span></li>
      <li>Support Google+ circle activities.<span> July 16, 2013 </span></li>
      <li>Support Sina Weibo emotions.<span> July 25, 2012 </span></li>
      <li>Update Sina Weibo to use OAuth2.0.<span> July 25, 2012 </span></li>
      <li>Support Twitter inline photos in tweets.<span> May 29, 2012 </span></li>
      <li>Update to support basic Google+ activities, only can list your own activities due to google+ api limitation.<span> Feb 15, 2012 </span></li>
      <li>Update to use Facebook SDK 3.0, and better parsing for Facebook feeds.<span> Feb 6, 2012 </span></li>
      <li>Google Buzz is not supported by Google anymore. We will update it to support Google+ soon!<span> Feb 2, 2012 </span></li>
      <li>Move to new webhosting server, running PHP5.2.* now<span> Aug 22, 2011 </span></li>
      <li>Fix Facebook news feed "comment" count bug<span> Mar 22, 2011 </span></li>
      <li>Change to use goo.gl shorten url service instead of Sina's<span> Mar 14, 2011 </span></li>
      <li>Fix Facebook news feed "like" count bug<span> Mar 10, 2011 </span></li>
      <li>Update new screenshot preview picture<span> Feb 27, 2011 </span></li>
      <li>Support video attachment correctly in Google buzz<span> Feb 26, 2011 </span></li>
      <li>Support Twitter "retweet/reply" functions<span> Feb 25, 2011 </span></li>
      <li>Fix Facebook "login"/"like" button conflict issue<span> Feb 23, 2011 </span></li>
      <li>Add 新浪微博"转发"和"评论"功能<span> Jan 28, 2011 </span></li>
      <li>Fix facebook ajax posting cross-domain issue<span> Jan 23, 2011 </span></li>
      <li>Fix twitter tweets of using HomeTimeLine instead of FriendTimeLine<span> Jan 19, 2011 </span></li>
      <li>Add Feedback tab for comments and bug reports.<span> Jan 18, 2011 </span></li>
      <li>Fully support different social network happening re-broadcasting(StreamIt)<span> Jan 15, 2011 </span></li>
      <li>Fixed facebook photo/link type wallpost display issues<span> Jan 14, 2011 </span></li>
      <li>Support URL shortening using Sina URL shortening service<span> Jan 11, 2011 </span></li>
      <li>Add google custom search box<span> Jan 9, 2011 </span></li>
      <li>Support ajax effect "posting" for happenings<span> Jan 6, 2011 </span></li>
      <li>Fix 新浪微博"@人名"和"#热门话题#" parsing issue<span> Jan 5, 2011 </span></li>
      <li>Add happening stream list auto refresh support<span> Jan 4, 2011 </span></li>
      <li>Fix "facebook" extended permission issues<span> Jan 2, 2011 </span></li>
      <li>Support status broadcasting to all supported social networks just in one post<span> Dec 31, 2010 </span></li>
      <li>Support caching to optimize speed in refreshing the page<span> Dec 24, 2010 </span></li>
      <li>Fixed session logout conflict issue for Sina Weibo<span> Dec 22, 2010 </span></li>
      <li>Used new iGoogle like theme template from Nettuts<span> Dec 20, 2010 </span></li>
      <li>Support 新浪微博转发及附件图片<span> Dec 18, 2010 </span></li>
      <li>Add 新浪微博更新<span> Dec 16, 2010 </span></li>
      <li>Support google buzz attachments correctly<span> Dec 13, 2010 </span></li>
      <li>Support ajax effect "refresh" for happenings<span> Dec 11, 2010 </span></li>
      <li>Support Google buzz with original buzz link for actions like "Comment" and "Like"<span> Nov 18, 2010 </span></li>
      <li>Support tweets "via", "location" and original tweet link for actions like "Retweet", "Favorite" and "Reply"<span> Nov 18, 2010 </span></li>
      <li>Solve session conflict issue between Twitter and Google Buzz<span> Nov 17, 2010 </span></li>
      <li>Support "Comments" and "likes" of Facebook and Google buzz "happenings"<span> Nov 16, 2010 </span></li>
      <li>Add Google buzz support<span> Nov 12, 2010 </span></li>
      <li>Add Facebook updates support<span> Oct 13, 2010</span></li>
      <li>Add Twitter tweets support<span> Sep 22, 2010 </span></li>  
    </ul>
   </div>
</div>

<div class="columnleft">
<h3>Screenshot <span>Preview</span></h3>
<br />
<div id="watermark_box"><a class="watermarklink" href="javascript:void(0);"><img src="../images/preview-10.png" height=0 width=0 class="watermark" onclick="zC('../images/FriendStreamNewScreenShot.png')"/><img src="../images/FriendStreamNewScreenShot.png" onclick="zC('../images/FriendStreamNewScreenShot.png', 1024, 800)" class="preview" /></a></div>
</div>

   </div>
   </li>
</ul>

<?php if ($fbme || isset($_SESSION['twitter']) || isset($_SESSION['gplusobj']) || isset($_SESSION['weibo'])) { 
         echo '-->';
} 
?>

</div> <!--intro-->


<div id="columns"> <!--footer-->
<ul id="column-footer" class="column">
  <li class="widget color-black" id="intro">
   <div class="widget-head">
    <h3>Site Footer</h3>
   </div>
   <div class="widget-content">
   <div class="columnleft" style="width:25%;">
   <h3>Site Partners</h3>
	<ul>
    <li><a target="_blank" href="http://www.pinamazon.tk"><strong>Pinamazon.tk</strong></a> - Your Pinterest style amazon mall</li>
    <li><a target="_blank" href="http://pintweet.tk/"><strong>Pintweet.tk</strong></a> - Pinterest style tweets wall</li>
    <li><a target="_blank" href="http://pinweibo.tk/"><strong>Pintweibo.tk</strong></a> - Pinterest style weibo wall</li>
    <li><a target="_blank" href="http://www.firsttimer.ca"><strong>Firsttimer.ca</strong></a> - Get things done yourself</li>
   <!--
	<li><a target="_blank" href="http://www.000webhost.com/489783.html"><strong>000webhost hosting</strong></a> - Free PHP Webhosting</li>
    <li><a target="_blank" href="http://getbarometer.com/"><strong>Barometer</strong></a> - feedback tabs for all</li>
    <li><a target="_blank" href="http://www.google.com/friendconnect/"><strong>Google Friend Connect</strong></a> - Google Friend Connect</li>
	<li><a target="_blank" href="http://www.ebuddy.com/"><strong>EBuddy</strong></a> - Web and mobile messaging</li>
    <li><a target="_blank" href="http://net.tutsplus.com/"><strong>Nettuts+</strong></a> - Web development tutorials</li>-->
    </ul>
</div>

<div class="columnleft" style="width:10%;">
   <h3>Links</h3>
	<ul>				
	<li><a target="_blank" href="http://www.twitter.com/"><strong>twitter.com</strong></a></li>
	<li><a target="_blank" href="http://www.facebook.com/"><strong>facebook.com</strong></a></li>
    <li><a target="_blank" href="http://www.google.com/+"><strong>google plus</strong></a></li>
    <li><a target="_blank" href="http://t.sina.com.cn"><strong>sina weibo</strong></a></li>
	<!--<li><a target="_blank" href="http://www.kaixin001.com/"><strong>kaixin001.com</strong></a></li>
	<li><a target="_blank" href="http://www.renren.com"><strong>renren.com</strong></a></li>	-->				
	</ul>
</div>

<div class="columnleft" style="width:25%;">      
   <h4>&copy; copyright 2010 - 2013 <a href="http://friendstream.ca">Friendstream.ca</a></h4>
	<ul>
     <li>
     <span class="notice"><p>Developed by: <a target="_blank" href="http://about.me/yehenrytian"><strong>Ye Henry Tian</strong></a></p></span>
     </li>
	 <!-- AddThis Button BEGIN -->
     <li><a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pub=xa-4b28078b55a57eae"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pub=xa-4b28078b55a57eae"></script>
     </li>
     <!-- AddThis Button END -->

      <li>
      <a href="//affiliates.mozilla.org/link/banner/27789"><img src="//affiliates.mozilla.org/media/uploads/banners/910443de740d4343fa874c37fc536bd89998c937.png" alt="Download: Fast, Fun, Awesome" /></a>
      </li>
    </ul>
</div>

<div class="columnleft" style="float:right;width:25%;">      
  <a href="http://s03.flagcounter.com/more/EzF"><img src="http://s03.flagcounter.com/count/EzF/bg=FFFFFF/txt=075FF7/border=0C9FCC/columns=2/maxflags=12/viewers=0/labels=1/pageviews=1/" alt="free counters" border="0"></a>
  
</div>

   </div>
   </li>
</ul>
</div> <!--footer-->

</div> <!--wrapper-->

<script type='text/javascript' src='../include/jquery-1.4.4.min.js?ver=1.4.4'></script>
    <script type="text/javascript">
        $(function() {
            var offset = $("#fssidebar").offset();
            var topPadding = 30;
            $(window).scroll(function() {
                if ($(window).scrollTop() > offset.top) {
                    $("#fssidebar").stop().animate({
                        marginTop: $(window).scrollTop() - offset.top + topPadding + 20
                    });
                } else {
                    $("#fssidebar").stop().animate({
                        marginTop: 30
                    });
                };
            });
        });
    </script>
<div class="sidebar" id="fssidebar">
<ul>
<li>
<a target="_blank" href="http://twitter.com/yehenrytian"><img src="../images/twitter-button.png" width="150" height="56" alt="follow me" /></a>
</li>
<li>
<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="yehenrytian">Tweet</a>
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
</li>
<li>
<br/>
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="http://friendstream.ca" layout="box_count" show_faces="true" font="arial"></fb:like>
</li>
<li>
<br/>
<!-- Place this tag in your head or just before your close body tag -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<!-- Place this tag where you want the +1 button to render -->
<g:plusone size="tall"></g:plusone>
</li>
<li>
<br/>
<script type="text/javascript" charset="utf-8">
(function(){
  var _w = 86 , _h = 50;
  var param = {
    url:location.href,
    type:'6',
    count:'1', /**是否显示分享数，1显示(可选)*/
    appkey:'1550075579', /**您申请的应用appkey,显示分享来源(可选)*/
    title:'', /**分享的文字内容(可选，默认为所在页面的title)*/
    pic:'http://friendstream.ca/images/fspre3cut.png', /**分享图片的路径(可选)*/
    ralateUid:'1880690955', /**关联用户的UID，分享微博会@该用户(可选)*/
    rnd:new Date().valueOf()
  }
  var temp = [];
  for( var p in param ){
    temp.push(p + '=' + encodeURIComponent( param[p] || '' ) )
  }
  document.write('<iframe allowTransparency="true" frameborder="0" scrolling="no" src="http://hits.sinajs.cn/A1/weiboshare.html?' + temp.join('&') + '" width="'+ _w+'" height="'+_h+'"></iframe>')
})()
</script>
</li>
<?php if (!$fbme && !isset($_SESSION['twitter']) && !isset($_SESSION['gplusobj']) && !isset($_SESSION['weibo'])) { 
         $disabled = true;
} 
?>
<li>

<div id="columns">
<ul id="column2" class="column" style="width:100%;">
  <li class="widget color-orange" style="margin: 5px 0px 0px 0px; padding:0px">
   <div class="widget-head">
    <h3>Happening</h3>
   </div>
   <div class="widget-content" style="padding-bottom:10px;">
    <form method="post" enctype="multipart/form-data" name="streamform" id="streamform">
     <div id="streambox" name="streambox" class="tweetbox">
     <textarea name="happening" oninput="textcount(this, 140, this.form.remain);" onfocus="if (this.value == 'What is happening?') this.value='';textcount(this, 140, this.form.remain);" onblur="if (this.value == '') this.value='What is happening?';textcount(this, 140, this.form.remain);" onkeydown="textcount(this, 140, this.form.remain);" onkeyup="textcount(this, 140, this.form.remain);" <?php if ($disabled) echo 'class="tweetareadisabled" disabled="disabled"'; else echo 'class="tweetarea"';?>>What is happening?</textarea></div>
     <br/><div align="right"><span class="notice">Remaining:</span><input class="remain" disabled name="remain" value=140></div>
     <div class="streamlist">
     <h4><span>Stream it on:</span></h4>
     <?php if (isset($_SESSION['twitter'])) {?>
     <div class="notice"><input type="checkbox" name="twitter" value="show" checked="checked"/><img src="../images/twitter.gif" alt="Twitter" style="width:16px; height:16px;">Twitter</div>
     <?php } else {?>
     <div class="disabled"><input type="checkbox" name="twitter" disabled="disabled"/><img src="../images/twitter.gif" alt="Twitter" style="width:16px; height:16px;"><span>Twitter(<a href="<?php echo $request_link; ?>" title="Sign in with Twitter">sign in</a>)</span></div>
     <?php }?>
     <?php if ($fbme) {?>
     <div class="notice"><input type="checkbox" name="facebook" value="show" checked="checked"/><img src="../images/facebook.gif" alt="Facebook" style="width:16px; height:16px;">Facebook</div>
     <?php } else {?>
     <div class="disabled"><input type="checkbox" name="facebook" disabled="disabled"/><img src="../images/facebook.gif" alt="Facebook" style="width:16px; height:16px;"><span>Facebook(<a href="<?php echo $facebook->getLoginUrl(array('req_perms' => 'read_stream,user_status,friends_status,user_location,friends_location,status_update,publish_stream')); ?>" title="Sign in with Facebook" target="_blank">sign in</a>)</span></div>
     <?php }?>
     <?php if (isset($_SESSION['gplusobj'])) {?>
     <div class="notice"><input type="checkbox" name="buzz" value="show" checked="checked"/><img src="../images/plus-icon-16x16.png" alt="Google Plus" style="width:16px; height:16px;">Google Plus</div>
     <?php } else {?>
     <div class="disabled"><input type="checkbox" name="buzz" disabled="disabled"/><img src="../images/plus-icon-16x16.png" alt="Google Plus" style="width:16px; height:16px;"><span>Google Plus(<a href="javascript:void(0);" onclick="buzzLogin()" title="Sign in Google+">sign in</a>)</span></div>
     <?php }?>
     <?php if (isset($_SESSION['weibo'])) {?>
     <div class="notice"><input type="checkbox" name="weibo" value="show" checked="checked"/><img src="../images/weibo128.png" alt="Weibo" style="width:16px; height:16px;">新浪微博</div>
     <?php } else {?>
     <div class="disabled"><input type="checkbox" name="weibo" disabled="disabled"/><img src="../images/weibo128.png" alt="Weibo" style="width:16px; height:16px;"><span>新浪微博(<a href="<?php echo $aurl; ?>" title="Sign in with Sina Weibo">登录</a>)</span></div>
     <?php }?>
     </div>
     <?php if (!$disabled) echo '<div><input class="button streamit" type="button" value="StreamIt" onclick="streamAjaxPost()"/><input class="button reset" type="button" value="Reset" onclick="tweetBoxReset()"/></div>';?>
    </form>
   </div>
  </li>
</ul>
</div> 
</li>
</ul>
</div>


</div> <!--friendstreamwrapper-->
    
    <script type="text/javascript" src="../include/ajax2.js"></script>
    <script type="text/javascript" src="../include/jquery-1.2.6.min.js"></script>
    <script type="text/javascript" src="jquery-ui-personalized-1.6rc2.min.js"></script>
    <script type="text/javascript" src="inettuts.js"></script>
    
<style type='text/css'>@import url('http://getbarometer.s3.amazonaws.com/install/assets/css/barometer.css');</style>
<script type="text/javascript" charset="utf-8">
  BAROMETER.load('f17xCQkSM27COTrmjXKrE');
</script>
</body>
</html>
