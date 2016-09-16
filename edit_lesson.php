<?php 
include('config.php');
isScriptInstalled();
validateUserSession();

$sql = "select * from cb_lesson_master where admin_id='".$_SESSION['userdata']['admin_pid']."' and lesson_pid='".$_REQUEST['id']."'";
$query = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($query) <= 0) {
	$_SESSION['error_message'] = $lang['invalid_lesson'];
	header("Location:lesson.php");
	exit();
} else {
	$row = mysql_fetch_assoc($query);
}
include('include/header.php');
include('include/sidebar.php');
?>
<div class="page add-lesson col-lg-10 col-sm-8">
	<h3><?php echo $lang['edit_lesson']; ?></h3>
	<form role="form" id="edit-lesson" action="businesslayer.php" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="action" value="7">
		<div class="form-border">
		    <div class="form-group">
		        <label><?php echo $lang['title_field']; ?></label>
		        <input class="form-control" type="text" name="lesson_title" id="lesson_title" value="<?php echo stripslashes($row['lesson_title']); ?>" required>
		    </div>
		    <div class="form-group">
		        <textarea name="lesson_text" id="lesson_text" rows="10" cols="80"><?php echo stripslashes($row['lesson_text']); ?></textarea>
		    </div>
	    </div>
	    <div class="form-border">
		 	<h4><?php echo $lang['links_title']; ?></h4>
	    	<div class="form-group check-box">
	    		<input type="checkbox" name="display_links" id="display_links" value="1" <?php echo ($row['display_links']) ? "checked" : ""; ?>><label class="checkbox-inline" for="display_links"><?php echo $lang['dont_display_links']; ?></label>
	    	</div>
		    <div class="add-field1">
		    	<?php
		    	$sql2 = "select * from cb_lesson_links where lesson_id='".$_REQUEST['id']."' order by sort_order asc";
				$query2 = mysql_query($sql2) or die(mysql_error());
				if(mysql_num_rows($query2) > 0) {
					$loop = 0;
					?>
					<div class="list-lesson">
						<table width="100%">
					<?php
					while($row_links = mysql_fetch_assoc($query2)) {
						$get_visits = get_visits(array("entity_id" => $row_links['link_pid'], "entity_type" => 2));
		    	?>
		    		<tr class="links <?php echo ($loop % 2 == 0) ? "odd" : ""; ?>" data="<?php echo $row_links['link_pid']; ?>">
						<td>
							<div class="field1">
								<label><a href="<?php echo $row_links['link_url']; ?>" target="_blank"><?php echo $row_links['link_title']; ?></a></label>
							</div>
						</td>
						<td>
							<div class="edit_option">
								<input type="hidden" class="link_sort_order" name="link_sort_order[]" value="<?php echo $row_links['sort_order']; ?>">
								<input type="hidden" name="link_id[]" value="<?php echo $row_links['link_pid']; ?>">
								<a href="javascript:;"><?php echo $get_visits; ?></a>
								<a href="javascript:;"><span data="<?php echo $row_links['link_pid']; ?>" class="glyphicon <?php echo ($row_links['status'] == "0") ? "glyphicon-eye-close" : "glyphicon-eye-open";?> linkstatus" aria-hidden="true"></span></a>
								<a href="javascript:;"><span data="<?php echo $row_links['link_pid']; ?>" class="glyphicon glyphicon-pencil linkedit" aria-hidden="true"></span></a>
								<a href="javascript:;"><span data="<?php echo $row_links['link_pid']; ?>" class="glyphicon glyphicon-trash linkdelete" aria-hidden="true"></span></a>
								<a href="javascript:;"><span data="<?php echo $row_links['link_pid']; ?>" class="glyphicon glyphicon-arrow-down linkmovedown" aria-hidden="true"></span></a>
								<a href="javascript:;"><span data="<?php echo $row_links['link_pid']; ?>" class="glyphicon glyphicon-arrow-up linkmoveup" aria-hidden="true"></span></a>
							</div>
						</td>
					</tr>
			    <?php $loop++; }
				 ?> </table>	</div> <?php }  else { ?>
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
				<?php } ?>
		    </div>
		    <span class="form-group" id="add-tab1"><?php echo $lang['add_tab']; ?></span>
	    </div>
	    <div class="form-border">
		 	<h4><?php echo $lang['files_title']; ?></h4>
		 	<div class="form-group check-box">
		 		<input type="checkbox" name="display_files" id="display_files" value="1" <?php echo ($row['display_files']) ? "checked" : ""; ?>><label class="checkbox-inline" for="display_files"><?php echo $lang['dont_display_files']; ?></label>
		 	</div>
		    <div class="add-field2">
		    	<?php
		    	$sql3 = "select * from cb_lesson_files where lesson_id='".$_REQUEST['id']."' order by sort_order asc";
				$query3 = mysql_query($sql3) or die(mysql_error());
				if(mysql_num_rows($query3) > 0) {
					$loop = 0;
					?>
					<div class="list-lesson">
						<table width="100%">
					<?php
					while($row_files = mysql_fetch_assoc($query3)) {
						$get_visits = get_visits(array("entity_id" => $row_files['file_pid'], "entity_type" => 3));
		    	?>
			    	<tr class="files <?php echo ($loop % 2 == 0) ? "odd" : ""; ?>" data="<?php echo $row_files['file_pid']; ?>">
						<td>
							<div class="field1">
								<label href="<?php echo $row_files['file_url']; ?>"><?php echo $row_files['file_title']; ?></label>
							</div>
						</td>
						<td>
							<div class="field1">
								<label class="glyphicon <?php if($row_files['file_type']) { echo getFileTypeImage($row_files['file_type']); } else { if($row_files['file_url']) { echo "glyphicon-link"; } } ?>"></label>
							</div>
						</td>
						<td>
							<div class="edit_option">
								<input type="hidden" class="file_sort_order" name="file_sort_order[]" value="<?php echo $row_files['sort_order']; ?>">
								<input type="hidden" name="file_id[]" value="<?php echo $row_files['file_pid']; ?>">
								<a href="javascript:;"><?php echo $get_visits; ?></a>
								<a href="javascript:;"><span data="<?php echo $row_files['file_pid']; ?>" class="glyphicon <?php echo ($row_files['status'] == "0") ? "glyphicon-eye-close" : "glyphicon-eye-open";?> filestatus" aria-hidden="true"></span></a>
								<a href="javascript:;"><span data="<?php echo $row_files['file_pid']; ?>" class="glyphicon glyphicon-pencil fileedit" aria-hidden="true"></span></a>
								<a href="javascript:;"><span data="<?php echo $row_files['file_pid']; ?>" class="glyphicon glyphicon-trash filedelete" aria-hidden="true"></span></a>
								<a href="javascript:;"><span data="<?php echo $row_files['file_pid']; ?>" class="glyphicon glyphicon-arrow-down filemovedown" aria-hidden="true"></span></a>
								<a href="javascript:;"><span data="<?php echo $row_files['file_pid']; ?>" class="glyphicon glyphicon-arrow-up filemoveup" aria-hidden="true"></span></a>
							</div>
						</td>
					</tr>
				    <?php $loop++; }
					 ?> </table>	</div> <?php }  else { ?>
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
				<?php } ?>
		    </div>
		    <span class="form-group" id="add-tab2"><?php echo $lang['add_tab']; ?></span>
	    </div>
	    <div class="form-group">
	    	<input type="hidden" name="lesson_id" value="<?php echo $_REQUEST['id']; ?>">
	    	<input type="submit" class="btn btn-default save-btn" value="<?php echo $lang['save_button']; ?>">
	    </div>
    </form>    
</div>
<div class="modal fade" id="linkModal" tabindex="-1" role="dialog" aria-labelledby="linkModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="linkModalLabel">&nbsp;</h4>
      </div>
      <div class="modal-body">
        <div class="aa">
		    <div class="form-group">
		        <label><?php echo $lang['title_field']; ?></label>
		        <input class="form-control full-width" id="link_title">
		    </div>
		    <div class="form-group">
		        <label><?php echo $lang['url_field']; ?></label>
		        <input class="form-control full-width" id="link_url">
		    </div>
	    </div>
      </div>
      <div class="modal-footer">
      	<input type="hidden" id="edit_link_id">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['modal_close']; ?></button>
        <button type="button" class="btn btn-primary" id="updatelink"><?php echo $lang['modal_save']; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="fileModalLabel">&nbsp;</h4>
      </div>
      <div class="modal-body">
        <div class="aa">
		    <div class="form-group">
		        <label><?php echo $lang['title_field']; ?></label>
		        <input class="form-control full-width" id="file_title">
		    </div>
		    <div class="form-group">
		        <label><?php echo $lang['url_field']; ?></label>
		        <input class="form-control full-width" id="file_url">
		    </div>
		    <div class="form-group">
		    	<span class="upload-text"><?php echo $lang['or_upload_file']; ?></span>
				<input type="file" value="Choose file" id="uploaded_file">
		    </div>
	    </div>
      </div>
      <div class="modal-footer">
      	<input type="hidden" id="edit_file_id">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['modal_close']; ?></button>
        <button type="button" class="btn btn-primary" id="updatefile"><?php echo $lang['modal_save']; ?></button>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
.list-lesson {min-height: 0;}
</style>
<script>
var edit_link_obj;
var edit_file_obj;

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
	    language: '<?php echo $lang['ckeditor_lang'] ?>'
});

	$(document).on("click", ".linkdelete", function() {
		var obj = $(this);

		if(confirm("Do you really want to delete this link? This action can't be undone")) {
			showLoading();

			$.ajax({
				url: 'businesslayer.php',
				type: 'POST',
				data: {action: 8, link_id: obj.attr("data")},
				dataType: 'json',
				success: function(response) {
					hideLoading();
					if(response.result == "true") {
						obj.parents("tr").remove();
						alert(response.message);
					} else {
						alert(response.message);
					}
				},
				error: function() {
					hideLoading();
					alert(response.message);
				}
			});
		}
	});

	$(document).on("click", ".filedelete", function() {
		var obj = $(this);

		if(confirm("Do you really want to delete this file? This action can't be undone")) {
			showLoading();

			$.ajax({
				url: 'businesslayer.php',
				type: 'POST',
				data: {action: 9, file_id: obj.attr("data")},
				dataType: 'json',
				success: function(response) {
					hideLoading();
					if(response.result == "true") {
						obj.parents("tr").remove();
						alert(response.message);
					} else {
						alert(response.message);
					}
				},
				error: function() {
					hideLoading();
					alert(response.message);
				}
			});
		}
	});

	$(document).on("click", ".linkstatus", function() {
		var obj = $(this);
		showLoading();

		if($(this).hasClass("glyphicon-eye-open")) {
			var status = 0;
		} else {
			var status = 1;
		}

		$.ajax({
			url: 'businesslayer.php',
			type: 'POST',
			data: {action: 11, link_id: obj.attr("data"), status: status},
			dataType: 'json',
			success: function(response) {
				hideLoading();
				if(response.result == "true") {
					if(status == 0) {
						obj.removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
					} else {
						obj.removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open");
					}
					alert(response.message);
				} else {
					alert(response.message);
				}
			},
			error: function() {
				hideLoading();
				alert(response.message);
			}
		});
	});

	$(document).on("click", ".filestatus", function() {
		var obj = $(this);
		showLoading();

		if($(this).hasClass("glyphicon-eye-open")) {
			var status = 0;
		} else {
			var status = 1;
		}

		$.ajax({
			url: 'businesslayer.php',
			type: 'POST',
			data: {action: 12, file_id: obj.attr("data"), status: status},
			dataType: 'json',
			success: function(response) {
				hideLoading();
				if(response.result == "true") {
					if(status == 0) {
						obj.removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
					} else {
						obj.removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open");
					}
					alert(response.message);
				} else {
					alert(response.message);
				}
			},
			error: function() {
				hideLoading();
				alert(response.message);
			}
		});
	});

	$(document).on("click", ".linkmovedown,.linkmoveup", function() {
		var row = $(this).parents("tr:first");
        if ($(this).is(".linkmoveup")) {
            row.insertBefore(row.prev());
        } else {
            row.insertAfter(row.next());
        }

        var loop = 1;
        $(".link_sort_order").each(function() {
        	$(this).val(loop);
        	loop++;
        });
	});

	$(document).on("click", ".filemovedown,.filemoveup", function() {
		var row = $(this).parents("tr:first");
        if ($(this).is(".filemoveup")) {
            row.insertBefore(row.prev());
        } else {
            row.insertAfter(row.next());
        }

        var loop = 1;
        $(".file_sort_order").each(function() {
        	$(this).val(loop);
        	loop++;
        });
	});

	$(document).on("click", ".linkedit", function() {
		edit_link_obj = $(this).parents("tr");

		var title = $(this).parents("tr").find("td:eq(0)").find("div").find("label").find("a").html();
		var url = $(this).parents("tr").find("td:eq(0)").find("div").find("label").find("a").attr("href");

		$("#link_title").val(title);
		$("#link_url").val(url);
		$("#edit_link_id").val($(this).attr("data"));

		$("#linkModal").modal("show");
	});

	$(document).on("click", ".fileedit", function() {
		edit_file_obj = $(this).parents("tr");

		var title = $(this).parents("tr").find("td:eq(0)").find("div").find("label").html();
		var url = $(this).parents("tr").find("td:eq(0)").find("div").find("label").attr("href");

		$("#file_title").val(title);
		$("#file_url").val(url);
		$("#edit_file_id").val($(this).attr("data"));

		$("#fileModal").modal("show");
	});

	$(document).on("click", "#updatelink", function() {
		showLoading();

		var link_title = $("#link_title").val();
		var link_url = $("#link_url").val();
		var link_id = $("#edit_link_id").val();

		$.ajax({
			url: 'businesslayer.php',
			method: 'POST',
			data: {action: 13, link_title: link_title, link_url: link_url, link_id: link_id},
			dataType: 'json',
			success: function(response) {
				hideLoading();
				if(response.result == "true") {
					$("#linkModal").modal("hide");
					edit_link_obj.find("td:eq(0)").find("div").find("label").find("a").html(link_title);
					edit_link_obj.find("td:eq(0)").find("div").find("label").find("a").attr("href", link_url);
					alert(response.message);
				} else {
					alert(response.message);
				}
			},
			error: function() {
				hideLoading();
				alert(response.message);
			}
		});
	});

	$(document).on("click", "#updatefile", function() {
		showLoading();

		if($("#uploaded_file").val()) {
			var uploaded_file = $("#uploaded_file").prop("files")[0];
	        var form_data = new FormData();    
	        form_data.append("uploaded_file", uploaded_file);

	        var file_id = $("#edit_file_id").val();

	        $.ajax({
	            dataType: 'text',
	            cache: false,
	            contentType: false,
	            processData: false,
	            data: form_data,                         
	            type: 'post',       
	            url: "businesslayer.php?action=14&file_id="+file_id,
	            success: function(response) {
					if(response == "true") {
						updateFile(response);
					} else {
						hideLoading();
						alert(response.message);
					}
				},
				error: function() {
					hideLoading();
					alert(response.message);
				}
	        });
	    } else {
	    	updateFile();
	    }
	})
});

function updateFile() {
	var file_title = $("#file_title").val();
	var file_url = $("#file_url").val();
	var file_id = $("#edit_file_id").val();
	
	$.ajax({
		url: 'businesslayer.php',
		method: 'POST',
		data: {action: 15, file_title: file_title, file_url: file_url, file_id: file_id},
		dataType: 'json',
		success: function(response) {
			hideLoading();
			if(response.result == "true") {
				$("#fileModal").modal("hide");
				edit_file_obj.find("td:eq(0)").find("div").find("label").html(file_title);
				edit_file_obj.find("td:eq(0)").find("div").find("label").attr("href", file_url);
				edit_file_obj.find("td:eq(1)").find("div").find("label").attr("class", "glyphicon");
				edit_file_obj.find("td:eq(1)").find("div").find("label").addClass(response.file_type);
				alert(response.message);	
			} else {
				alert(response.message);
			}
		},
		error: function() {
			hideLoading();
			alert(response.message);
		}
	});
}
</script>
<?php include('include/footer.php') ?>