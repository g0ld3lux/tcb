<?php 
include('config.php');
isScriptInstalled();
if($_SESSION['user_id']) {
    header("Location:".SITE_URL);
    exit();
}
include('include/header_nonlogin.php');
?>
<div class="page">
    <div class="login-page">
      	<h3><?php echo $lang['forgotpassword_title']; ?></h3>
        <?php showNotificationMessage(); ?>
      	<form role="form" action="businesslayer.php" method="POST" id="login" data-toggle="validator">
            <input type="hidden" name="action" value="17">
      	    <div class="form-group">
      	        <label id="username"><?php echo $lang['username_field']; ?></label>
      	        <input class="form-control" type="text" name="email" id="email" required>
      	    </div>
      	    <div class="form-group">
      	    	<input type="submit" class="btn btn-default save-btn" value="<?php echo $lang['submit_button']; ?>">
      	    </div>
            <div class="form-group">
              <a href="login.php"><?php echo $lang['login_link']; ?></a>
            </div>
        </form>
    </div>
</div>    
<?php include('include/footer_nonlogin.php') ?>