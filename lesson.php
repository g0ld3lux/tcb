<?php 
include('config.php');
isScriptInstalled();
validateUserSession();
include('include/header.php');
include('include/sidebar.php');

$lessons = get_lessons($_SESSION['userdata']['admin_pid']);
?>
<div class="page lesson-listing col-lg-10 col-sm-8">
	<h3><?php echo $lang['page_lesson_title']; ?></h3>
	<?php showNotificationMessage(); ?>
	<form role="form" id="lesson-list">
		<div class="list-lesson">
			<table width="100%">
			<?php
			if(mysql_num_rows($lessons) > 0) {
				$loop = 0;
				while($row = mysql_fetch_assoc($lessons)) {
					$get_visits = get_visits(array("entity_id" => $row['lesson_pid'], "entity_type" => 1));
			?>
				<tr class="lessons <?php echo ($loop % 2 == 0) ? "odd" : ""; ?>" data="<?php echo $row['lesson_pid']; ?>">
					<td>
						<div class="field1">
							<label><?php echo stripslashes($row['lesson_title']); ?></label>
						</div>
					</td>
					<td>
						<div class="edit_option">
							<a href="javascript:;"><?php echo $get_visits; ?></a>
							<a href="javascript:;"><span data="<?php echo $row['lesson_pid']; ?>" class="glyphicon <?php echo ($row['status'] == "0") ? "glyphicon-eye-close" : "glyphicon-eye-open";?> lessonstatus" aria-hidden="true"></span></a>
							<a href="edit_lesson.php?id=<?php echo $row['lesson_pid']; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
							<a href="javascript:;"><span data="<?php echo $row['lesson_pid']; ?>" class="glyphicon glyphicon-trash lessondelete" aria-hidden="true"></span></a>
							<a href="javascript:;"><span data="<?php echo $row['lesson_pid']; ?>" class="glyphicon glyphicon-arrow-down lessonmovedown" aria-hidden="true"></span></a>
							<a href="javascript:;"><span data="<?php echo $row['lesson_pid']; ?>" class="glyphicon glyphicon-arrow-up lessonmoveup" aria-hidden="true"></span></a>
						</div>
					</td>
				</tr>
			<?php $loop++; }
			} else { ?>
				<tr class="odd">
					<td colspan="2"><?php echo $lang['nolessonfound'];?></td>
				</tr>
			<?php } ?>
			</table>
		</div>
		<?php
			if(mysql_num_rows($lessons) > 0) {
		?>		
			<div class="form-group"><input type="button" class="btn btn-default save-btn" value="<?php echo $lang['save_button']; ?>"></div>
		<?php } ?>	
	</form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$(document).on("click", ".lessonstatus", function() {
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
			data: {action: 5, lesson_id: obj.attr("data"), status: status},
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

	$(document).on("click", ".lessondelete", function() {
		var obj = $(this);

		if(confirm("Do you really want to delete this lesson?")) {
			showLoading();

			$.ajax({
				url: 'businesslayer.php',
				type: 'POST',
				data: {action: 6, lesson_id: obj.attr("data")},
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

	$(document).on("click", ".lessonmovedown,.lessonmoveup", function() {
		var row = $(this).parents("tr:first");
        if ($(this).is(".lessonmoveup")) {
            row.insertBefore(row.prev());
        } else {
            row.insertAfter(row.next());
        }
	});

	$(document).on("click", ".save-btn", function() {
		showLoading();

		var lessons = new Array();
		$(".lessons").each(function() {
			lessons.push($(this).attr("data"));
		});

		$.ajax({
			url: 'businesslayer.php',
			type: 'POST',
			data: {action: 10, lessons: lessons},
			dataType: 'json',
			success: function(response) {
				hideLoading();
				if(response.result == "true") {
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
});
</script>
<?php include('include/footer.php') ?>