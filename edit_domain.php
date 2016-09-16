<?php 
include('config.php');
isScriptInstalled();
validateUserSession();

$sql = "select * from cb_domains where id='".$_REQUEST['id']."'";
$query = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($query) <= 0) {
	$_SESSION['error_message'] = $lang['invalid_lesson'];
	header("Location:domains.php");
	exit();
} else {
	$row = mysql_fetch_assoc($query);
}
include('include/header.php');
include('include/sidebar.php');
?>
<div class="page add-lesson col-lg-10 col-sm-8">
	<h3><?php echo $lang['edit_domain']; ?></h3>
	<form role="form" id="edit-lesson" action="businesslayer.php" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="action" value="21">
		<div class="form-border">
		    <div class="form-group">
		        <label><?php echo $lang['domain_field']; ?></label>
		        <input class="form-control" type="text" name="domain" id="lesson_title" value="<?php echo stripslashes($row['domain']); ?>" required>
		    </div>
	    </div>

	    <div class="form-group">
	    	<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>">
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
	    language: 'en'
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