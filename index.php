<?php 
include('config.php');
isScriptInstalled();
validateUserSession();
include('include/header.php');
include('include/sidebar.php');
?>
<div class="welcomepage col-lg-10 col-sm-8">
	<h3><?php echo $lang['welcome_aboard']; ?></h3>
	<div class="all-info">
		<ul>
			<li><?php echo $lang['click_settings']; ?></li>
			<li><?php echo $lang['click_lessons']; ?></li>
			<li><?php echo $lang['click_embed']; ?></li>
			<li><?php echo $lang['click_addlesson']; ?></li>
			<li class="last"><?php echo $lang['click_changepwd']; ?></li>
		</ul>
	</div>
</div>
<?php include('include/footer.php') ?>