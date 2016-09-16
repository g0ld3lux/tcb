<?php 
include('config.php');
isScriptInstalled();
validateUserSession();
include('include/header.php');
include('include/sidebar.php');

$lessons = get_domains();
?>
<div class="page setting col-lg-10 col-sm-8">
	<h3><?php echo $lang['domains_title']; ?></h3>
	<?php showNotificationMessage(); ?>
	<form role="form" id="lesson-list">
		<div class="list-lesson">
			<table width="100%">
				<?php
				if(count($lessons) > 0) {
					$loop = 0;
					foreach($lessons as $row) {
//						$get_visits = get_visits(array("entity_id" => $row['lesson_pid'], "entity_type" => 1));
						?>
						<tr class="lessons <?php echo ($loop % 2 == 0) ? "odd" : ""; ?>" data="<?php echo $row['lesson_pid']; ?>">
							<td>
								<div class="field1">
									<label><?php echo stripslashes($row['domain']); ?></label>
								</div>
							</td>
							<td>
								<div class="edit_option">
									<a href="edit_domain.php?id=<?php echo $row['id']; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
									<a href="javascript:;"><span data="<?php echo $row['id']; ?>" class="glyphicon glyphicon-trash lessondelete" aria-hidden="true"></span></a>
								</div>
							</td>
						</tr>
						<?php $loop++; }
				} else { ?>
					<tr class="odd">
						<td colspan="2"><?php echo $lang['nodomainsfound'];?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
		<?php
		if(false && count($lessons) > 0) {
			?>
			<div class="form-group"><input type="button" class="btn btn-default save-btn" value="<?php echo $lang['save_button']; ?>"></div>
		<?php } ?>
	</form>
</div>

<script type="text/javascript">
        $(document).ready(function() {
            $(document).on("click", ".lessondelete", function() {
                var obj = $(this);

                if(confirm("Do you really want to delete this domain?")) {
                    showLoading();

                    $.ajax({
                        url: 'businesslayer.php',
                        type: 'POST',
                        data: {action: 20, id: obj.attr("data")},
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

        });
    </script>
<?php include('include/footer.php') ?>