<?php 
include('config.php');
isScriptInstalled();
validateUserSession();
include('include/header.php');
include('include/sidebar.php');
?>
<div class="page change-pwd col-lg-10 col-sm-8">
	<h3><?php echo $lang['change_pwd']; ?></h3>
	<?php showNotificationMessage(); ?>
	<form role="form" id="change-pass" action="businesslayer.php" method="POST">
		<input type="hidden" name="action" value="3">
	    <div class="form-group">
	        <label id="pwd"><?php echo $lang['current_pwd']; ?></label>
	        <input class="form-control" type="password" name="current_password" id="current_password">
	    </div>
	    <div class="form-group">
	        <label id="pwd"><?php echo $lang['new_pwd']; ?></label>
	        <input class="form-control" type="password" name="new_password" id="new_password">
	    </div>
	    <div class="form-group">
	        <label id="pwd"><?php echo $lang['repeatnew_pwd']; ?></label>
	        <input class="form-control" type="password" name="repeat_password" id="repeat_password">
	    </div>
	    <div class="form-group">
    		<input type="submit" class="btn btn-default save-btn" value="<?php echo $lang['save_button']; ?>">
    	</div>
    </form>
</div>
<?php include('include/footer.php') ?>