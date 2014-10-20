<?php
/**
 * @file
 * Clears PHP sessions and redirects to the connect page.
 */
 
/* Load and clear sessions */
session_start();

if (isset($_GET['weibo']) && $_GET['weibo'] == 'clear') {
   unset($_SESSION['last_key']);
   unset($_SESSION['weibo']);
   unset($_SESSION['weibostatus']);
   if (!isset($_SESSION['twitter']) && !isset($_SESSION['buzzobj']) && !isset($_SESSION['facebook']))
      {
      $_SESSION = array();
	  // remove the session cookie
      if (isset($_COOKIE[session_name()]))
         setcookie(session_name(), '', time() - 45000); 

	  session_destroy();
	  }
  
  header('Location: ../../friendstream/');
}
else if (isset($_GET['buzz']) && $_GET['buzz'] == 'clear') {
   unset($_SESSION['buzzobj']);
   unset($_SESSION['gplusobj']);
   if (!isset($_SESSION['twitter']) && !isset($_SESSION['weibo']) && !isset($_SESSION['facebook']))
      {
      $_SESSION = array();
	  // remove the session cookie
      if (isset($_COOKIE[session_name()]))
         setcookie(session_name(), '', time() - 45000); 
	  session_destroy();
	  }
   header('Location: ../../friendstream/');
}
else if (isset($_GET['twitter']) && $_GET['twitter'] == 'clear') {
  unset($_SESSION['access_token']);
  unset($_SESSION['twitter']);
  if  (!isset($_SESSION['weibo']) && !isset($_SESSION['buzzobj']) && !isset($_SESSION['facebook']))
      {
      $_SESSION = array();
	  // remove the session cookie
      if (isset($_COOKIE[session_name()]))
         setcookie(session_name(), '', time() - 45000); 
	  session_destroy();
	  }
   
   header('Location: ../../friendstream/');
}
else if (isset($_GET['facebook']) && $_GET['facebook'] == 'clear') {
   unset($_SESSION['facebook']);
   if ((!isset($_SESSION['twitter']) && !isset($_SESSION['buzzobj']) && !isset($_SESSION['weibo'])))
      {
      $_SESSION = array();
	  // remove the session cookie
      if (isset($_COOKIE[session_name()]))
         setcookie(session_name(), '', time() - 45000); 

	  session_destroy();
	  }
  
  header('Location: ../../friendstream/');
}
else{
$_SESSION = array();

// remove the session cookie
if (isset($_COOKIE[session_name()]))
   setcookie(session_name(), '', time() - 45000); 

session_destroy();
/* Redirect to page with the connect to Twitter option. */
header('Location: ../../friendstream/');
}

?>