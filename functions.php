<?php
function admin_login($params) {
    $sql = "select admin_pid,username from cb_admin where (username='".$params['email']."' or email='".$params['email']."') and password='".md5($params['password'])."'";
    $query = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($query) > 0) {
        $row = mysql_fetch_assoc($query);
        $_SESSION['userdata'] = $row;
        return true;
    } else {
        return false;
    }
}

function forgot_password($params) {
    $sql = "select admin_pid,username,email from cb_admin where (username='".$params['email']."' or email='".$params['email']."')";
    $query = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($query) > 0) {
        $row = mysql_fetch_assoc($query);

        $password = generateRandomPassword();

        $sql2 = "update cb_admin set password='".md5($password)."' where admin_pid='".$row['admin_pid']."'";
        $query2 = mysql_query($sql2) or die(mysql_error());

        $message = 'Hello '.$row['username'].',<br><br> Please use the following username and password to access your installation.<br><br>';
        $message .= "<b>Email:</b> ".$row['email'].'<br>';
        $message .= "<b>Password:</b> ".$password.'<br><br>';
        $message .= SITE_URL;

        SendMail($row['email'], 'New Password', $message);

        return true;
    } else {
        return false;
    }
}

function get_settings($admin_id) {
    $sql = "select * from cb_settings where admin_id='".$admin_id."'";
    $query = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($query) > 0) {
        $row = mysql_fetch_assoc($query);
        return $row;
    } else {
        return false;
    }
}

function get_domains() {
    $sql = "select * from cb_domains";
    $query = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($query) > 0) {
        $all = array();
        while ($row = mysql_fetch_assoc($query)) {
            $all[] = $row;
        }
        return $all;
    } else {
        return array();
    }
}

function delete_domain($params) {
    $sql2 = "delete from cb_domains where id='".$params['id']."'";
    if (mysql_query($sql2))
        return 1;
    else
        return 0;
}

function update_settings($params) {
    $sql = "update cb_settings set lesson_title_color='".$params['lesson_title_color']."',lesson_title_size='".$params['lesson_title_size']."',lesson_title_font='".$params['lesson_title_font']."',lesson_text_color='".$params['lesson_text_color']."',lesson_text_size='".$params['lesson_text_size']."',lesson_text_font='".$params['lesson_text_font']."',lesson_text_aligment='".$params['lesson_text_aligment']."' where admin_id='".$_SESSION['userdata']['admin_pid']."'";
    $query = mysql_query($sql) or die(mysql_error());
    if($query) {
        return true;
    } else {
        return false;
    }
}

function change_password($params) {
    $sql = "select admin_pid from cb_admin where password='".md5($params['current_password'])."' and admin_pid='".$_SESSION['userdata']['admin_pid']."'";
    $query = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($query) > 0) {
        $sql2 = "update cb_admin set password='".md5($params['new_password'])."' where admin_pid='".$_SESSION['userdata']['admin_pid']."'";
        $query2 = mysql_query($sql2) or die(mysql_error());
        if($query2) {
            return 1;
        } else {
            return 0;
        }
    } else {
        return 2;
    }
}

function get_lessons($admin_id) {
    $sql = "select * from cb_lesson_master where admin_id='".$admin_id."' order by sort_order asc";
    $query = mysql_query($sql) or die(mysql_error());
    return $query;
}

function get_lesson_max_sortorder() {
    $sql = "select max(sort_order) as max_sort_order from cb_lesson_master where admin_id='".$_SESSION['userdata']['admin_pid']."'";
    $query = mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_assoc($query);
    return $row['max_sort_order'];
}

function get_file_max_sortorder($lesson_id) {
    $sql = "select max(sort_order) as max_sort_order from cb_lesson_files where lesson_id='".$lesson_id."'";
    $query = mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_assoc($query);
    return $row['max_sort_order'];
}

function get_link_max_sortorder($lesson_id) {
    $sql = "select max(sort_order) as max_sort_order from cb_lesson_links where lesson_id='".$lesson_id."'";
    $query = mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_assoc($query);
    return $row['max_sort_order'];
}

function add_lesson($params) {
    if (!isset($params['display_files']))
        $params['display_files'] = 0;
    if (!isset($params['display_links']))
        $params['display_links'] = 0;

    $sort_order = get_lesson_max_sortorder() + 1;
    $sql = "insert into cb_lesson_master set admin_id='".$_SESSION['userdata']['admin_pid']."',lesson_title='".$params['lesson_title']."',lesson_text='".$params['lesson_text']."',lesson_status='1',status='1',display_links='".$params['display_links']."',display_files='".$params['display_files']."',sort_order='".$sort_order."',date_created='".date("Y-m-d H:i:s")."',date_modified='".date("Y-m-d H:i:s")."'";
    $query = mysql_query($sql) or die(mysql_error());

    $lesson_id = mysql_insert_id();
    if($lesson_id) {
        if($params['link_title'] && count($params['link_title']) > 0 ) {
            $loop = 0;
            foreach($params['link_title'] as $links) {
                $sort_order = get_link_max_sortorder($lesson_id) + 1;

                $sql = "insert into cb_lesson_links set lesson_id='".$lesson_id."',link_title='".$links."',link_url='".$params['link_url'][$loop]."',sort_order='".$sort_order."',status='1',date_created='".date("Y-m-d H:i:s")."'";
                $query = mysql_query($sql) or die(mysql_error());
                $loop++;
            }
        }

        if($params['file_title'] && count($params['file_title']) > 0 ) {
            $loop = 0;
            foreach($params['file_title'] as $files) {

                if($_FILES['uploaded_file']['name'][$loop]) {
                    $file_path = time()."_".rand(0,9999999999999)."_".preg_replace('/\s+/', '', $_FILES['uploaded_file']['name'][$loop]);
                    move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$loop], UPLOADS_PATH."/".$file_path);

                    $file_type = get_file_mime_type(UPLOADS_PATH."/".$file_path);
                } else {
                    $file_path = "";
                    $file_type = "";
                }

                $sort_order = get_file_max_sortorder($lesson_id) + 1;

                $sql = "insert into cb_lesson_files set lesson_id='".$lesson_id."',file_title='".$files."',file_url='".$params['file_url'][$loop]."',uploaded_file='".$file_path."',file_type='".$file_type."',sort_order='".$sort_order."',status='1',date_created='".date("Y-m-d H:i:s")."'";
                $query = mysql_query($sql) or die(mysql_error());
                $loop++;
            }
        }
        return true;
    } else {
        return false;
    }
}

function add_domain($params) {
    $sql = "insert into cb_domains set domain='" . $params['domain'] . "'";
    $query = mysql_query($sql) or die(mysql_error());

    $lesson_id = mysql_insert_id();
    if($lesson_id) {
        return true;
    } else {
        return false;
    }
}

function change_lesson_status($params) {
    $sql = "update cb_lesson_master set status='".$params['status']."' where admin_id='".$_SESSION['userdata']['admin_pid']."' and lesson_pid='".$params['lesson_id']."'";
    $query = mysql_query($sql) or die(mysql_error());
    return 1;
}

function delete_lesson($params) {
    $sql = "select * from cb_lesson_master where admin_id='".$_SESSION['userdata']['admin_pid']."' and lesson_pid='".$params['lesson_id']."'";
    $query = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($query)) {
        $sql = "select * from cb_lesson_files where lesson_id='".$params['lesson_id']."'";
        $query = mysql_query($sql) or die(mysql_error());
        if(mysql_num_rows($query) > 0) {
            while($row = mysql_fetch_assoc($query)) {
                if($row['uploaded_file']) {
                    @unlink(UPLOADS_PATH."/".$row['uploaded_file']);
                }
                $sql2 = "delete from cb_lesson_files where lesson_id='".$params['lesson_id']."' and file_pid='".$row['file_pid']."'";
                mysql_query($sql2) or die(mysql_error());
            }
        }

        $sql3 = "delete from cb_lesson_links where lesson_id='".$params['lesson_id']."'";
        mysql_query($sql3) or die(mysql_error());

        $sql4 = "delete from cb_lesson_master where admin_id='".$_SESSION['userdata']['admin_pid']."' and lesson_pid='".$params['lesson_id']."'";
        mysql_query($sql4) or die(mysql_error());
        return 1;
    } else {
        return 0;
    }
}

function edit_lesson($params) {
    if (!isset($params['display_files']))
        $params['display_files'] = 0;
    if (!isset($params['display_links']))
        $params['display_links'] = 0;

    $lesson_id = $params['lesson_id'];
    if($lesson_id) {
        $sql = "select * from cb_lesson_master where admin_id='".$_SESSION['userdata']['admin_pid']."' and lesson_pid='".$lesson_id."'";
        $query = mysql_query($sql) or die(mysql_error());
        if(mysql_num_rows($query)) {
            $sql = "update cb_lesson_master set lesson_title='".$params['lesson_title']."',lesson_text='".$params['lesson_text']."',display_links='".$params['display_links']."',display_files='".$params['display_files']."',date_modified='".date("Y-m-d H:i:s")."' where admin_id='".$_SESSION['userdata']['admin_pid']."' and lesson_pid='".$lesson_id."'";
            $query = mysql_query($sql) or die(mysql_error());

            if($params['link_title'] && count($params['link_title']) > 0) {
                $loop = 0;
                foreach($params['link_title'] as $links) {
                    $sort_order = get_link_max_sortorder($lesson_id) + 1;

                    $sql = "insert into cb_lesson_links set lesson_id='".$lesson_id."',link_title='".$links."',link_url='".$params['link_url'][$loop]."',sort_order='".$sort_order."',status='1',date_created='".date("Y-m-d H:i:s")."'";
                    $query = mysql_query($sql) or die(mysql_error());
                    $loop++;
                }
            }

            if($params['link_id'] && count($params['link_id']) > 0) {
                $loop = 0;
                foreach($params['link_id'] as $links) {
                    $sql = "update cb_lesson_links set sort_order='".$params['link_sort_order'][$loop]."' where lesson_id='".$lesson_id."' and link_pid='".$links."'";
                    $query = mysql_query($sql) or die(mysql_error());
                    $loop++;
                }
            }

            if($params['file_title'] && count($params['file_title']) > 0) {
                $loop = 0;
                foreach($params['file_title'] as $files) {

                    if($_FILES['uploaded_file']['name'][$loop]) {
                        $file_path = time()."_".rand(0,9999999999999)."_".preg_replace('/\s+/', '', $_FILES['uploaded_file']['name'][$loop]);
                        move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$loop], UPLOADS_PATH."/".$file_path);

                        $file_type = get_file_mime_type(UPLOADS_PATH."/".$file_path);
                    } else {
                        $file_path = "";
                        $file_type = "";
                    }

                    $sort_order = get_file_max_sortorder($lesson_id) + 1;

                    $sql = "insert into cb_lesson_files set lesson_id='".$lesson_id."',file_title='".$files."',file_url='".$params['file_url'][$loop]."',uploaded_file='".$file_path."',file_type='".$file_type."',sort_order='".$sort_order."',status='1',date_created='".date("Y-m-d H:i:s")."'";
                    $query = mysql_query($sql) or die(mysql_error());
                    $loop++;
                }
            }

            if($params['file_id'] && count($params['file_id']) > 0) {
                $loop = 0;
                foreach($params['file_id'] as $files) {
                    $sql = "update cb_lesson_files set sort_order='".$params['file_sort_order'][$loop]."' where lesson_id='".$lesson_id."' and file_pid='".$files."'";
                    $query = mysql_query($sql) or die(mysql_error());
                    $loop++;
                }
            }

            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function get_file($file_id) {
    $sql = "select * from cb_lesson_files where file_pid='".$file_id."'";
    $query = mysql_query($sql) or die(mysql_error());
    return mysql_fetch_assoc($query);
}

function delete_link($params) {
    $sql = "delete from cb_lesson_links where link_pid='".$params['link_id']."'";
    mysql_query($sql) or die(mysql_error());
    return true;
}

function delete_file($params) {
    $sql = "select * from cb_lesson_files where file_pid='".$params['file_id']."'";
    $query = mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_assoc($query);
    if($row['uploaded_file']) {
        @unlink(UPLOADS_PATH."/".$row['uploaded_file']);
    }

    $sql2 = "delete from cb_lesson_files where file_pid='".$params['file_id']."'";
    mysql_query($sql2) or die(mysql_error());
    return true;
}

function change_lesson_sortorder($params) {
    if($params['lessons'] && count($params['lessons']) > 0) {
        $sort_order = 1;
        foreach($params['lessons'] as $lesson_id) {
            $sql = "update cb_lesson_master set sort_order='".$sort_order."' where admin_id='".$_SESSION['userdata']['admin_pid']."' and lesson_pid='".$lesson_id."'";
            mysql_query($sql) or die(mysql_error());
            $sort_order++;
        }
        return true;
    } else {
        return false;
    }
}

function change_link_status($params) {
    $sql = "update cb_lesson_links set status='".$params['status']."' where link_pid='".$params['link_id']."'";
    $query = mysql_query($sql) or die(mysql_error());
    return true;
}

function change_file_status($params) {
    $sql = "update cb_lesson_files set status='".$params['status']."' where file_pid='".$params['file_id']."'";
    $query = mysql_query($sql) or die(mysql_error());
    return true;
}

function update_lesson_link($params) {
    $sql = "update cb_lesson_links set link_title='".$params['link_title']."',link_url='".$params['link_url']."' where link_pid='".$params['link_id']."'";
    $query = mysql_query($sql) or die(mysql_error());
    return true;
}

function update_lesson_file($params) {
    $get_file = get_file($params['file_id']);

    if($_FILES['uploaded_file']['name']) {
        $file_path = time()."_".rand(0,9999999999999)."_".preg_replace('/\s+/', '', $_FILES['uploaded_file']['name']);
        move_uploaded_file($_FILES['uploaded_file']['tmp_name'], UPLOADS_PATH."/".$file_path);

        $file_type = get_file_mime_type(UPLOADS_PATH."/".$file_path);

        if($get_file['uploaded_file']) {
            @unlink(UPLOADS_PATH."/".$get_file['uploaded_file']);
        }
    } else {
        $file_path = $get_file['uploaded_file'];
        $file_type = $get_file['file_type'];
    }

    $sql = "update cb_lesson_files set uploaded_file='".$file_path."',file_type='".$file_type."' where file_pid='".$params['file_id']."'";
    $query = mysql_query($sql) or die(mysql_error());
    return true;
}

function update_lesson_filedata($params) {
    $sql = "update cb_lesson_files set file_title='".$params['file_title']."',file_url='".$params['file_url']."' where file_pid='".$params['file_id']."'";
    $query = mysql_query($sql) or die(mysql_error());
    return true;
}

function add_embed_code($params) {
    if(!$params['embed_id']) {
        $sql = "insert into cb_embed_master set admin_id='".$_SESSION['userdata']['admin_pid']."',embed_code='".$params['embed_code']."',lock_code_status='".$params['lock_code_status']."',date_created='".date("Y-m-d H:i:s")."'";
        $query = mysql_query($sql) or die(mysql_error());
    } else {
        $sql = "update cb_embed_master set embed_code='".$params['embed_code']."',lock_code_status='".$params['lock_code_status']."' where admin_id='".$_SESSION['userdata']['admin_pid']."' and embed_pid='".$params['embed_id']."'";
        $query = mysql_query($sql) or die(mysql_error());
    }
    return true;
}

function get_embed_code($admin_id) {
    $sql = "select * from cb_embed_master where admin_id='".$admin_id."'";
    $query = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($query) > 0) {
        return mysql_fetch_assoc($query);
    } else {
        return false;
    }
}

function get_lessons_public($admin_id) {
    $sql = "select * from cb_lesson_master where admin_id='".$admin_id."' and status='1' order by sort_order asc";
    $query = mysql_query($sql) or die(mysql_error());
    return $query;
}

function get_lesson_details($admin_id, $lesson_id) {
    $sql = "select * from cb_lesson_master where admin_id='".$admin_id."' and lesson_pid='".$lesson_id."'";
    $query = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($query) > 0) {
        return mysql_fetch_assoc($query);
    } else {
        return false;
    }
}

function check_visit($params) {
    $sql = "select * from cb_visits where entity_id='".$params['entity_id']."' and entity_type='".$params['entity_type']."' and ip_address='".$_SERVER['REMOTE_ADDR']."'";
    $query = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($query) > 0) {
        return true;
    } else {
        return false;
    }
}

function add_visits($params) {
    $sql = "insert into cb_visits set entity_id='".$params['entity_id']."',entity_type='".$params['entity_type']."',ip_address='".$_SERVER['REMOTE_ADDR']."',date_created='".date("Y-m-d H:i:s")."'";
    $query = mysql_query($sql) or die(mysql_error());
}

function get_visits($params) {
    $sql = "select count(*) as total from cb_visits where entity_id='".$params['entity_id']."' and entity_type='".$params['entity_type']."'";
    $query = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($query) > 0) {
        $row = mysql_fetch_assoc($query);
        return $row['total'];
    } else {
        return false;
    }
}

function edit_domain($params) {
    $id = $params['id'];
    if($id) {
        $sql = "update cb_domains set domain='".$params['domain']
            . "' where id='" . $id . "'";
        if (mysql_query($sql)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

?>