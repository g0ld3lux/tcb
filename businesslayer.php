<?php
include_once("config.php");

if($_REQUEST['action']) {
	switch($_REQUEST['action']) {
		case 1:
		$params = addSlashesArray($_REQUEST);
		$result = admin_login($params);
		if($result) {
			header("Location:".SITE_URL);
			exit();
		} else {
			$_SESSION['error_message'] = $lang['invalid_login'];
			header("Location:".SITE_URL."/login.php");
			exit();
		}
		break;

		case 2:
		$params = addSlashesArray($_REQUEST);
		$result = update_settings($params);
		if($result) {
			$_SESSION['success_message'] = $lang['success_settings_updated'];
			header("Location:".SITE_URL."/settings.php");
			exit();
		} else {
			$_SESSION['error_message'] = $lang['error_settings_updated'];
			header("Location:".SITE_URL."/settings.php");
			exit();
		}
		break;

		case 3:
		$params = addSlashesArray($_REQUEST);
		$result = change_password($params);
		if($result == 1) {
			$_SESSION['success_message'] = $lang['success_password_updated'];
			header("Location:".SITE_URL."/change_password.php");
			exit();
		} else if($result == 2) {
			$_SESSION['error_message'] = $lang['error_passwordnotmatch_updated'];
			header("Location:".SITE_URL."/change_password.php");
			exit();
		} else {
			$_SESSION['error_message'] = $lang['error_password_updated'];
			header("Location:".SITE_URL."/change_password.php");
			exit();
		}
		break;

		case 4:
		$params = addSlashesArray($_REQUEST);

		$result = add_lesson($params);
		if($result == 1) {
			$_SESSION['success_message'] = $lang['success_lesson_added'];
			header("Location:".SITE_URL."/lesson.php");
			exit();
		} else {
			$_SESSION['error_message'] = $lang['error_lesson_added'];
			header("Location:".SITE_URL."/add_lesson.php");
			exit();
		}
		break;



		case 5:
		$params = addSlashesArray($_REQUEST);

		$result = change_lesson_status($params);
		if($result == 1) {
			echo json_encode(array("result" => "true", "message" => $lang['status_success_message']));
			exit();
		} else {
			echo json_encode(array("result" => "false", "message" => $lang['status_error_message']));
			exit();
		}
		break;

		case 6:
		$params = addSlashesArray($_REQUEST);

		$result = delete_lesson($params);
		if($result == 1) {
			echo json_encode(array("result" => "true", "message" => $lang['lessondelete_success_message']));
			exit();
		} else {
			echo json_encode(array("result" => "false", "message" => $lang['lessondelete_error_message']));
			exit();
		}
		break;

		case 7:
		$params = addSlashesArray($_REQUEST);

		$result = edit_lesson($params);
		if($result == 1) {
			$_SESSION['success_message'] = $lang['success_lesson_updated'];
			header("Location:".SITE_URL."/lesson.php");
			exit();
		} else {
			$_SESSION['error_message'] = $lang['error_lesson_updated'];
			header("Location:".SITE_URL."/add_lesson.php");
			exit();
		}
		break;

		case 8:
		$params = addSlashesArray($_REQUEST);

		$result = delete_link($params);
		if($result == 1) {
			echo json_encode(array("result" => "true", "message" => $lang['success_link_deleted']));
			exit();
		} else {
			echo json_encode(array("result" => "false", "message" => $lang['error_link_deleted']));
			exit();
		}
		break;

		case 9:
		$params = addSlashesArray($_REQUEST);

		$result = delete_file($params);
		if($result == 1) {
			echo json_encode(array("result" => "true", "message" => $lang['success_file_deleted']));
			exit();
		} else {
			echo json_encode(array("result" => "false", "message" => $lang['error_file_deleted']));
			exit();
		}
		break;

		case 10:
		$params = addSlashesArray($_REQUEST);

		$result = change_lesson_sortorder($params);
		if($result == 1) {
			echo json_encode(array("result" => "true", "message" => $lang['success_sortorder_update']));
			exit();
		} else {
			echo json_encode(array("result" => "false", "message" => $lang['error_sortorder_update']));
			exit();
		}
		break;

		case 11:
		$params = addSlashesArray($_REQUEST);

		$result = change_link_status($params);
		if($result == 1) {
			echo json_encode(array("result" => "true", "message" => $lang['status_success_message']));
			exit();
		} else {
			echo json_encode(array("result" => "false", "message" => $lang['status_error_message']));
			exit();
		}
		break;

		case 12:
		$params = addSlashesArray($_REQUEST);

		$result = change_file_status($params);
		if($result == 1) {
			echo json_encode(array("result" => "true", "message" => $lang['status_success_message']));
			exit();
		} else {
			echo json_encode(array("result" => "false", "message" => $lang['status_error_message']));
			exit();
		}
		break;

		case 13:
		$params = addSlashesArray($_REQUEST);

		$result = update_lesson_link($params);
		if($result == 1) {
			echo json_encode(array("result" => "true", "message" => $lang['linkupdate_success_message']));
			exit();
		} else {
			echo json_encode(array("result" => "false", "message" => $lang['linkupdate_error_message']));
			exit();
		}
		break;

		case 14:
		$params = addSlashesArray($_REQUEST);

		$result = update_lesson_file($params);
		if($result == 1) {
			echo "true";
			exit();
		} else {
			echo $lang['fileupload_error_message'];
			exit();
		}
		break;

		case 15:
		$params = addSlashesArray($_REQUEST);

		$result = update_lesson_filedata($params);
		if($result == 1) {
			$get_file = get_file($params['file_id']);
			echo json_encode(array("result" => "true", "message" => $lang['fileupdate_success_message'], "file_type" => getFileTypeImage($get_file['file_type'])));
			exit();
		} else {
			echo json_encode(array("result" => "false", "message" => $lang['fileupdate_error_message']));
			exit();
		}
		break;

		case 16:
		$params = addSlashesArray($_REQUEST);

		$result = add_embed_code($params);
		if($result == 1) {
			$_SESSION['success_message'] = $lang['embedcode_success_message'];
			header("Location:".SITE_URL."/embed_code.php");
			exit();
		} else {
			$_SESSION['error_message'] = $lang['embedcode_error_message'];
			header("Location:".SITE_URL."/embed_code.php");
			exit();
		}
		break;

		case 17:
		$params = addSlashesArray($_REQUEST);
		$result = forgot_password($params);
		if($result) {
			$_SESSION['success_message'] = $lang['forgotpassword_success_message'];
			header("Location:".SITE_URL."/login.php");
			exit();
		} else {
			$_SESSION['error_message'] = $lang['forgotpassword_error_message'];
			header("Location:".SITE_URL."/forgotpassword.php");
			exit();
		}
		break;

		case 18:
		$params = addSlashesArray($_REQUEST);
		$result = check_visit($params);
		if(!$result) {
			add_visits($params);
		}
		break;

        case 19:
            $params = addSlashesArray($_REQUEST);

            $result = add_domain($params);
            if($result == 1) {
                $_SESSION['success_message'] = $lang['success_domain_added'];
                header("Location:".SITE_URL."/domains.php");
                exit();
            } else {
                $_SESSION['error_message'] = $lang['error_domain_added'];
                header("Location:".SITE_URL."/add_domain.php");
                exit();
            }
            break;

        case 20:
            $params = addSlashesArray($_REQUEST);

            $result = delete_domain($params);
            if($result == 1) {
                echo json_encode(array("result" => "true", "message" => $lang['domaindelete_success_message']));
                exit();
            } else {
                echo json_encode(array("result" => "false", "message" => $lang['domaindelete_error_message']));
                exit();
            }
            break;
        case 21:
            $params = addSlashesArray($_REQUEST);

            $result = edit_domain($params);
            if($result == 1) {
                $_SESSION['success_message'] = $lang['success_lesson_updated'];
                header("Location:".SITE_URL."/domains.php");
                exit();
            } else {
                $_SESSION['error_message'] = $lang['error_lesson_updated'];
                header("Location:".SITE_URL."/edit_domains.php");
                exit();
            }
            break;

	}
}
?>