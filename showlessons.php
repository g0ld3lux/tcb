<?php
//$http_origin = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_SCHEME) . '://' . parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
//header("Access-Control-Allow-Origin: $http_origin");

include('config.php');

if(!$_GET['id']) {
	exit();
}

$referer = $_SERVER['HTTP_REFERER'];
$domains = get_domains();
$found =  false;
foreach($domains as $v) {
    if(strpos($referer, $v['domain']) !== false) {
        $found = true;
        break;
    }
}
if (!$found && count($domains))
    exit($lang['no_access']);

$get_lessons = get_lessons_public($_GET['id']);

$get_settings = get_settings($_GET['id']);
?>
<!DOCTYPE html>
<head>
	<title>Lessons</title>
	<link rel="stylesheet" type="text/css" href="assets/css/custom.css">
	<style type="text/css">
	a{font-size: 15px;color:<?php echo $get_settings['lesson_title_color']; ?>;font-size:<?php echo $get_settings['lesson_title_size']; ?>;font-family:<?php echo $get_settings['lesson_title_font']; ?>;}
	</style>
</head>
<body>
	<div id="page-container" class="page added-lesson col-lg-10 col-sm-8">
		<?php
		if(mysql_num_rows($get_lessons) <= 0) {

		} else {
			?>
			<div class="list-lesson">
				<table width="100%">
					<?php
					$loop = 0;
					while($row = mysql_fetch_assoc($get_lessons)) {
						?>
						<tr class="lessons <?php echo ($loop % 2 == 0) ? "odd" : ""; ?>">
							<td><div class="field1"><label><a href="viewlesson.php?id=<?php echo $_GET['id']; ?>&amp;lesson_id=<?php echo $row['lesson_pid']; ?>"><?php echo stripslashes($row['lesson_title']); ?></a></label></div>
							</td>
						</tr>	
						<?php
						$loop++;
					}
					?>
				</table>
			</div>
			<?php
		}
		?>
	</div>
	<script type="text/javascript">
  	  //parent.AdjustIframeHeight(document.getElementById("page-container").scrollHeight);
          parent.postMessage(document.getElementById("page-container").scrollHeight, '*');
	</script>	
</body>
</html>
