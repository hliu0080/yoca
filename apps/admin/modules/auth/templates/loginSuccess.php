<header>
	<a href="http://www.yocausa.org" target="_blank"><img src="/img/yoca/square-logo-rounded.png" /></a>
</header>
	
<section>
	<?php include_partial('login_form', array('form' => $form)) ?>
</section>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#login_username').focus();
});
</script>