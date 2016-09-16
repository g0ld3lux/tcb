<?php 
include('config.php');
isScriptInstalled();
validateUserSession();
include('include/header.php');
include('include/sidebar.php');

$get_settings = get_settings($_SESSION['userdata']['admin_pid']);
?>
<div class="page setting col-lg-10 col-sm-8">
	<h3><?php echo $lang['settings_title']; ?></h3>
	<?php showNotificationMessage(); ?>
    <form role="form" id="lesson-title" action="businesslayer.php" method="POST">
    	<input type="hidden" name="action" value="2">
	 	<div class="form-border">
	 	<h4><?php echo $lang['form_lesson_title']; ?></h4>
	    <div class="form-group">
	        <label><?php echo $lang['font_color']; ?></label>
	        <input type="color" class="form-control colorbox" name="lesson_title_color" id="lesson_title_color" value="<?php echo $get_settings['lesson_title_color']; ?>">
	    </div>
	    <div class="form-group">
	        <label><?php echo $lang['font_size']; ?></label>
	        <input class="form-control" name="lesson_title_size" id="lesson_title_size" value="<?php echo $get_settings['lesson_title_size']; ?>">
	    </div>
	    <div class="form-group">
	        <label><?php echo $lang['font_title']; ?></label>
	        <select class="form-control" name="lesson_title_font" id="lesson_title_font">
	        	<option disabled="disabled" placeholder="Select"><?php echo $lang['select_option']; ?></option>
	        	<option <?php if($lang['font_arial'] == $get_settings['lesson_title_font']) { echo 'selected'; } ?>><?php echo $lang['font_arial']; ?></option>
	        	<option <?php if($lang['font_verdana'] == $get_settings['lesson_title_font']) { echo 'selected'; } ?>><?php echo $lang['font_verdana']; ?></option>
	        	<option <?php if($lang['font_timesnewroman'] == $get_settings['lesson_title_font']) { echo 'selected'; } ?>><?php echo $lang['font_timesnewroman']; ?></option>
	        	<option <?php if($lang['font_opensans'] == $get_settings['lesson_title_font']) { echo 'selected'; } ?>><?php echo $lang['font_opensans']; ?></option>
	        	<option <?php if($lang['font_roboto'] == $get_settings['lesson_title_font']) { echo 'selected'; } ?>><?php echo $lang['font_roboto']; ?></option>
	        	<option <?php if($lang['font_lato'] == $get_settings['lesson_title_font']) { echo 'selected'; } ?>><?php echo $lang['font_lato']; ?></option>
	        	<option <?php if($lang['font_oswald'] == $get_settings['lesson_title_font']) { echo 'selected'; } ?>><?php echo $lang['font_oswald']; ?></option>
	        	<option <?php if($lang['font_ptsans'] == $get_settings['lesson_title_font']) { echo 'selected'; } ?>><?php echo $lang['font_ptsans']; ?></option>
	        	<option <?php if($lang['font_ubuntu'] == $get_settings['lesson_title_font']) { echo 'selected'; } ?>><?php echo $lang['font_ubuntu']; ?></option>
	        </select>
	    </div>
		</div>
		<div class="form-border">
	 	<h4><?php echo $lang['form_lesson_text']; ?></h4>
	    <div class="form-group">
	        <label><?php echo $lang['font_color']; ?></label>
	        <input type="color" class="form-control colorbox" name="lesson_text_color" id="lesson_text_color" value="<?php echo $get_settings['lesson_text_color']; ?>">
	    </div>
	    <div class="form-group">
	        <label><?php echo $lang['font_size']; ?></label>
	        <input class="form-control" name="lesson_text_size" id="lesson_text_size" value="<?php echo $get_settings['lesson_text_size']; ?>">
	    </div>
	    <div class="form-group">
	        <label><?php echo $lang['font_title']; ?></label>
	        <select class="form-control" name="lesson_text_font" id="lesson_text_font">
	        	<option disabled="disabled" placeholder="Select"><?php echo $lang['select_option']; ?></option>
	        	<option <?php if($lang['font_arial'] == $get_settings['lesson_text_font']) { echo 'selected'; } ?>><?php echo $lang['font_arial']; ?></option>
	        	<option <?php if($lang['font_verdana'] == $get_settings['lesson_text_font']) { echo 'selected'; } ?>><?php echo $lang['font_verdana']; ?></option>
	        	<option <?php if($lang['font_timesnewroman'] == $get_settings['lesson_text_font']) { echo 'selected'; } ?>><?php echo $lang['font_timesnewroman']; ?></option>
	        	<option <?php if($lang['font_opensans'] == $get_settings['lesson_text_font']) { echo 'selected'; } ?>><?php echo $lang['font_opensans']; ?></option>
	        	<option <?php if($lang['font_roboto'] == $get_settings['lesson_text_font']) { echo 'selected'; } ?>><?php echo $lang['font_roboto']; ?></option>
	        	<option <?php if($lang['font_lato'] == $get_settings['lesson_text_font']) { echo 'selected'; } ?>><?php echo $lang['font_lato']; ?></option>
	        	<option <?php if($lang['font_oswald'] == $get_settings['lesson_text_font']) { echo 'selected'; } ?>><?php echo $lang['font_oswald']; ?></option>
	        	<option <?php if($lang['font_ptsans'] == $get_settings['lesson_text_font']) { echo 'selected'; } ?>><?php echo $lang['font_ptsans']; ?></option>
	        	<option <?php if($lang['font_ubuntu'] == $get_settings['lesson_text_font']) { echo 'selected'; } ?>><?php echo $lang['font_ubuntu']; ?></option>
	        </select>
	    </div>
	    <div class="form-group">
		    <!--
	        <label><?php echo $lang['text_alignment']; ?></label>
	        <select class="form-control" name="lesson_text_aligment" id="lesson_text_aligment">
	        	<option <?php if($lang['text_left'] == $get_settings['lesson_text_aligment']) { echo 'selected'; } ?>><?php echo $lang['text_left']; ?></option>
	        	<option <?php if($lang['text_right'] == $get_settings['lesson_text_aligment']) { echo 'selected'; } ?>><?php echo $lang['text_right']; ?></option>
	        	<option <?php if($lang['text_justified'] == $get_settings['lesson_text_aligment']) { echo 'selected'; } ?>><?php echo $lang['text_justified']; ?></option>
	        </select>
	        -->
	    </div>
    	</div>
    	<div class="form-group">
    		<input type="submit" class="btn btn-default save-btn" value="<?php echo $lang['save_button']; ?>">
    	</div>
    </form>
</div>

<?php include('include/footer.php') ?>