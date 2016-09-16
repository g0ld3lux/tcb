<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $lang['site_title']; ?></title>
    
    <!-- Bootstrap Core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato|Open+Sans|Oswald|PT+Sans|Roboto|Ubuntu" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/sb-admin.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="assets/js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="assets/js/ckeditor.js"></script>
    <script type="text/javascript">
        function showLoading() {
            $(".loading").show();
        }

        function hideLoading() {
            $(".loading").hide();
        }
    </script>
    
   
</head>
<body>
    <div class="loading">
        <div class="spinner">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
    </div>
    <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><?php echo $lang['turbo_course_builder']; ?></a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav collapse navbar-collapse navbar-ex1-collapse">
                <li><a href="domains.php"><?php echo $lang['domains_menu']; ?></a></li>
                <li><a href="settings.php"><?php echo $lang['setting_menu']; ?></a></li>
                <li><a href="lesson.php"><?php echo $lang['page_lesson_title']; ?></a></li>
                <li><a href="embed_code.php"><?php echo $lang['embed_menu']; ?></a></li>
                <li><a href="change_password.php"><?php echo $lang['changepwd_menu']; ?></a></li>
                <li><a href="logout.php"><?php echo $lang['logout_menu']; ?></a></li>
            </ul>
            
            <!-- /.navbar-collapse -->
        </nav>