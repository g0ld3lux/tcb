<?php 
include('config.php');
isScriptInstalled();
validateUserSession();
include('include/header.php');
include('include/sidebar.php');
?>
<div class="page add-lesson col-lg-10 col-sm-8">
	<h3><?php echo $lang['add_lesson']; ?></h3>
	<form role="form" id="add-lesson" action="businesslayer.php" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="action" value="4">
		<div class="form-border">
		    <div class="form-group">
		        <label><?php echo $lang['title_field']; ?></label>
		        <input class="form-control" type="text" name="lesson_title" id="lesson_title" required>
		    </div>
		    <div class="form-group">
		        <textarea name="lesson_text" id="lesson_text" rows="10" cols="80"></textarea>
		    </div>
	    </div>
	    <div class="form-border">
		 	<h4><?php echo $lang['links_title']; ?></h4>
	    	<div class="form-group check-box">
	    		<input type="checkbox" name="display_links" id="display_links" value="1"><label class="checkbox-inline" for="display_links"><?php echo $lang['dont_display_links']; ?></label>
	    	</div>
	    	<span class="form-group" id="add-tab1"><?php echo $lang['add_tab']; ?></span>
		    <div class="add-field1">
			    <div class="aa">
				    <div class="form-group">
				        <label><?php echo $lang['title_field']; ?></label>
				        <input class="form-control full-width" name="link_title[]">
				    </div>
				    <div class="form-group">
				        <label><?php echo $lang['url_field']; ?></label>
				        <input class="form-control full-width" name="link_url[]">
				    </div>
				    <span class="form-group remove-tab"><?php echo $lang['remove_tab']; ?></span>
			    </div>
		    </div>
	    </div>
	    <div class="form-border">
		 	<h4><?php echo $lang['files_title']; ?></h4>
		 	<div class="form-group check-box">
		 		<input type="checkbox" name="display_files" id="display_files" value="1"><label class="checkbox-inline" for="display_files"><?php echo $lang['dont_display_files']; ?></label>
		 	</div>
		 	<span class="form-group" id="add-tab2"><?php echo $lang['add_tab']; ?></span>
		    <div class="add-field2">
			    <div class="aa">
				    <div class="form-group">
				        <label><?php echo $lang['title_field']; ?></label>
				        <input class="form-control full-width" name="file_title[]">
				    </div>
				    <div class="form-group">
				        <label><?php echo $lang['url_field']; ?></label>
				        <input class="form-control full-width" name="file_url[]">
				    </div>
				    <div class="form-group">
				    	<span class="upload-text"><?php echo $lang['or_upload_file']; ?></span>
	    				<input type="file" value="Choose file" name="uploaded_file[]">
				    </div>
				    <span class="form-group remove-tab"><?php echo $lang['remove_tab']; ?></span>
			    </div>
		    </div>
	    </div>
	    <div class="form-group">
	    	<input type="submit" class="btn btn-default save-btn" value="<?php echo $lang['save_button']; ?>">
	    </div>
    </form>    
</div>
<script>
$(function() {
	$(document).on('click', '#add-tab1', function() {
		$('<div class="aa"><div class="form-group"><label><?php echo $lang['title_field']; ?></label><input class="form-control full-width" name="link_title[]"></div><div class="form-group"><label><?php echo $lang['url_field']; ?></label><input class="form-control full-width" name="link_url[]"></div><span class="form-group remove-tab"><?php echo $lang['remove_tab']; ?></span></div>').appendTo('.add-field1');
	});

	$(document).on('click', '#add-tab2', function() {
		$('<div class="aa"><div class="form-group"><label><?php echo $lang['title_field']; ?></label><input class="form-control full-width" name="file_title[]"></div><div class="form-group"><label><?php echo $lang['url_field']; ?></label><input class="form-control full-width" name="file_url[]"></div><div class="form-group"><span class="upload-text"><?php echo $lang['or_upload_file']; ?></span><input type="file" value="Choose file" name="uploaded_file[]"></div><span class="form-group remove-tab"><?php echo $lang['remove_tab']; ?></span></div>').appendTo('.add-field2');
	});

	$(document).on('click', '.remove-tab', function() {
		$(this).parents('.aa').remove();
	});

	CKEDITOR.replace('lesson_text', {
	    language: 'en'
	});
});
</script>
<?php include('include/footer.php') ?>