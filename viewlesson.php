<?php
include('config.php');

if(!$_GET['id'] || !$_GET['lesson_id']) {
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

$lesson_details = get_lesson_details($_GET['id'], $_GET['lesson_id']);
if(!$lesson_details) {
    exit();
}

$get_settings = get_settings($_GET['id']);

$params = array('entity_id' => $_GET['lesson_id'], 'entity_type' => 1);

if(!check_visit($params)) {
    add_visits($params);
}
?>
<!DOCTYPE html>
<head>
    <title>Lessons</title>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <style type="text/css">
        .lesson_title{font-size: 15px;color:<?php echo $get_settings['lesson_title_color']; ?>;font-size:<?php echo $get_settings['lesson_title_size']; ?>;font-family:<?php echo $get_settings['lesson_title_font']; ?>;}
        .lesson_text{color:<?php echo $get_settings['lesson_text_color']; ?>;font-size:<?php echo $get_settings['lesson_text_size']; ?>;font-family:<?php echo $get_settings['lesson_text_font']; ?>;text-align:<?php echo $get_settings['lesson_text_aligment']; ?>; }
        .lesson_text img{display: block;margin: 0 auto;}

        .lesson_text{text-align:left; }
    </style>
    <script src="assets/js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on("click", ".visit_link", function() {
                $.ajax({
                    url: 'businesslayer.php',
                    type: 'POST',
                    data: {action: 18, entity_type: 2, entity_id: $(this).attr("data")}
                });
            });

            $(document).on("click", ".visit_file", function() {
                $.ajax({
                    url: 'businesslayer.php',
                    type: 'POST',
                    data: {action: 18, entity_type: 3, entity_id: $(this).attr("data")}
                });
            });
        });

    </script>


</head>
<body>
<div id="page-container" class="page added-lesson col-lg-10 col-sm-8">
    <a href="showlessons.php?id=<?php echo $_GET['id']; ?>">&lt;&nbsp;Back to lessons</a>
    <h2 class="lesson_title"><?php echo stripslashes($lesson_details['lesson_title']); ?></h2>
    <h5 class="lesson_text"><?php echo stripslashes($lesson_details['lesson_text']); ?></h5>

    <?php
    $sql2 = "select * from cb_lesson_links where lesson_id='".$_GET['lesson_id']."' and status='1' order by sort_order asc";
    $query2 = mysql_query($sql2) or die(mysql_error());
    if(mysql_num_rows($query2) > 0 && $lesson_details['display_links']) {
        ?>
        <h4><?php echo $lang['links_title']; ?></h4>
        <?php
        while($row_links = mysql_fetch_assoc($query2)) {
            ?>
            <h5><a class="visit_link" data="<?php echo $row_links['link_pid']; ?>" href="<?php echo $row_links['link_url']; ?>" target="_blank"><?php echo $row_links['link_title']; ?></a></h5>
        <?php }
    } ?>

    <?php
    $sql3 = "select * from cb_lesson_files where lesson_id='".$_GET['lesson_id']."' and status='1' order by sort_order asc";
    $query3 = mysql_query($sql3) or die(mysql_error());
    if(mysql_num_rows($query3) > 0 && $lesson_details['display_files']) {
        ?>
        <h4><?php echo $lang['files_title']; ?></h4>
        <?php
        while($row_files = mysql_fetch_assoc($query3)) {
            ?>
            <h5><a class="visit_file" data="<?php echo $row_files['file_pid']; ?>" href="<?php if($row_files['uploaded_file']) { echo SITE_URL.'/uploads/'.$row_files['uploaded_file']; } else { echo $row_files['file_url']; } ?>" target="_blank"><?php echo $row_files['file_title']; ?></a></h5>
        <?php }
    } ?>
</div>
<script type="text/javascript">
    //parent.AdjustIframeHeight(document.getElementById("page-container").scrollHeight);
    parent.postMessage(document.getElementById("page-container").scrollHeight, '*');
</script>

</body>
</html>
