<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<!--[if IE 8]>    <html class="no-js ie8 ie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9 ie" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    
    <!-- Load in css by usertype -->
    <?php if($sf_user->getAttribute('usertype')=='Admin'):?>
    	<link rel="stylesheet" type="text/css" media="screen" href="/css/wuxia-green.css">
    <?php elseif($sf_user->getAttribute('usertype')=='Member'):?>
    	<link rel="stylesheet" type="text/css" media="screen" href="/css/wuxia-red.css">
    <?php elseif($sf_user->getAttribute('usertype')=='Mentor'):?>
    	<link rel="stylesheet" type="text/css" media="screen" href="/css/wuxia-purple.css">
    <?php elseif($sf_user->getAttribute('usertype')=='Mentee'):?>
    	<link rel="stylesheet" type="text/css" media="screen" href="/css/wuxia-blue.css">
    <?php else:?>
    	<link rel="stylesheet" type="text/css" media="screen" href="/css/wuxia-red.css">
    <?php endif?>
    
    <?php include_stylesheets() ?>
    
    <!-- Fav and touch icons -->
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/img/icons/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/img/icons/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="/img/icons/apple-touch-icon-57-precomposed.png">
    
    <!-- JS Libs -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/libs/jquery.js"><\/script>')</script>
    <?php include_javascripts() ?>
    <script>
		$(document).ready(function(){
			
			// Navbar tooltips
			$('.navbar [title]').tooltip({
				placement: 'bottom'
			});
			
			// Content tooltips
			$('[role=main] [title]').tooltip({
				placement: 'top'
			});
			
			// Dropdowns
			$('.dropdown-toggle').dropdown();
			
		});
	</script>
  </head>
  <body>
    
    <?php echo $sf_content ?>
    
    <!-- Main footer -->
	<footer class="container">
		<nav>
			<ul>
				<li>&copy; 2013 Young Overseas Chinese Association, a 501c3 nonprofit organization.</li>
				<li><a href="mailto:<?php echo sfConfig::get('app_email_support')?>">Support</a></li>
			</ul>
		</nav>
	</footer>
	<!-- /Main footer -->
  </body>
</html>