<?php
include('config.php');
if(DB_NAME) {
    header("Location:".SITE_URL);
    exit();
}
include('include/header_nonlogin.php');
?>
<div class="page">
    <div class="register-page">
      <h3><?php echo $lang['install_title']; ?></h3>
      <?php showNotificationMessage(); ?>
      <form role="form" action="run_install.php" id="install" method="POST" data-error="" required>
          <div class="form-group">
              <label><?php echo $lang['dbhost_field']; ?></label>
              <input class="form-control" type="text" id="DB_HOST" name="DB_HOST" data-error="" required>
          </div>
          <div class="form-group">
              <label><?php echo $lang['dbuser_field']; ?></label>
              <input class="form-control" type="text" id="DB_USER" name="DB_USER" data-error="" required>
          </div>
          <div class="form-group">
              <label><?php echo $lang['dbpass_field']; ?></label>
              <input class="form-control" type="password" id="DB_PASS" name="DB_PASS" data-error="" required>
              
          </div>
          <div class="form-group">
              <label><?php echo $lang['dbname_field']; ?></label>
              <input class="form-control" type="text" id="DB_NAME" name="DB_NAME" data-error="" required>
          </div>
          <div class="form-group">
              <label><?php echo $lang['adminemail_field']; ?></label>
              <input class="form-control" type="text" id="Email" name="Email" data-error="Email address is invalid" required>
          </div>
          <div class="form-group">
              <label><?php echo $lang['adminusername_field']; ?></label>
              <input class="form-control" type="text" id="Username" name="Username" data-error="" required>
          </div>
          <div class="form-group">
              <label><?php echo $lang['adminpassword_field']; ?></label>
              <input class="form-control" type="password" id="Password" name="Password" data-error="" required>
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-default save-btn" name="install" value="<?php echo $lang['submit_button']; ?>">
          </div>
        </form>
    </div>
</div>    
<?php include('include/footer_nonlogin.php') ?>