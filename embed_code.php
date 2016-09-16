<?php 


include('config.php');
isScriptInstalled();
validateUserSession();
include('include/header.php');
include('include/sidebar.php');

$get_embed_code = get_embed_code($_SESSION['userdata']['admin_pid']);
?>
<div class="page embed-code col-lg-10 col-sm-8">
	<form role="form" id="embedcode-form" action="businesslayer.php" method="POST">
		<input type="hidden" name="action" value="16">
		<input type="hidden" name="embed_id" value="<?php echo $get_embed_code['embed_pid'];?>">
		<h3><?php echo $lang['embed_code']; ?></h3>
		<?php showNotificationMessage(); ?>
		<p><?php echo $lang['copy_code_note']; ?></p>
		<div class="embedcode-box col-lg-12">
			<textarea name="embed_code" id="embed_code" rows="9"><iframe src="<?php echo SITE_URL;?>/showlessons.php?id=<?php echo $_SESSION['userdata']['admin_pid'];?>" id="form-iframe" onload="AdjustIframeHeightOnLoad()" scrolling="no" width="100%" height="100%" frameborder="0" style="margin:0;border:none; overflow:hidden;"></iframe>
<script>
  window.addEventListener("message", receiveMessage, false);
  function receiveMessage(e){
      document.getElementById("form-iframe").style.height = (parseInt(e.data) + 10) + "px";
  }
</script>
</textarea>
		</div>
<!--		<div class="form-group check-box">
		 	<input type="checkbox" name="lock_code_status" id="lock_code_status" value="1" <?php /*if($get_embed_code['lock_code_status']) { echo "checked"; } */?>><label for="lock_code_status" class="checkbox-inline"><?php /*echo $lang['lock_code']; */?></label>
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-default save-btn" value="<?php /*echo $lang['save_button']; */?>">
		</div>-->
	</form>
</div>
<?php include('include/footer.php') ?>