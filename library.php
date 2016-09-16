<?php
function addSlashesArray($array) {
	foreach ($array as $key => $val) {
		if (is_array($val)) {
			$array[$key] = addSlashesArray($val);
		} else {
			$array[$key] = addslashes($val);
		}
	}
	return $array;
}

function stripSlashesArray($array) {
	foreach ($array as $key => $val) {
		if (is_array($val)) {
			$array[$key] = stripSlashesArray($val);
		} else {
			$array[$key] = stripslashes($val);
		}
	}
	return $array;
}

function encryptString($string) {
	return base64_encode(base64_encode(base64_encode(base64_encode(base64_encode($string)))));
}

function decryptString($string) {
	return base64_decode(base64_decode(base64_decode(base64_decode(base64_decode($string)))));
}

function showNotificationMessage() {
    if($_SESSION['success_message']) {
        echo '<div class="alert alert-success">'.$_SESSION['success_message'].'</div>';
        unset($_SESSION['success_message']);
    }
    if($_SESSION['error_message']) {
        echo '<div class="alert alert-danger">'.$_SESSION['error_message'].'</div>';
        unset($_SESSION['error_message']);
    }
}

function validateUserSession() {
    if(!$_SESSION['userdata']) {
        $_SESSION['error_message'] = "Please login to continue.";
        header("Location:".SITE_URL."/login.php");
        exit();
    }
}

function generateRandomPassword() {
    $length = 6;
    $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Length of character list
    $chars_length = (strlen($chars) - 1);
    // Start our string
    $string = $chars{rand(0, $chars_length)};
    // Generate random string
    for ($i = 1; $i < $length; $i = strlen($string))
    {
    // Grab a random character from our list
    $r = $chars{rand(0, $chars_length)};
    // Make sure the same two characters don't appear next to each other
    if ($r != $string{$i - 1}) $string .=  $r;
    }
    // Return the string
    return $string;
}

function isScriptInstalled() {
    if(!DB_NAME) {
        header("Location:install.php");
        exit();
    }
}

function getBaseUrl() {
    // first get http protocol if http or https
    $base_url = (isset($_SERVER['HTTPS']) &&
    $_SERVER['HTTPS']!='off') ? 'https://' : 'http://';
    // get default website root directory
    $tmpURL = dirname(__FILE__);
    // when use dirname(__FILE__) will return value like this "C:\xampp\htdocs\my_website",
    //convert value to http url use string replace, 
    // replace any backslashes to slash in this case use chr value "92"
    $tmpURL = str_replace(chr(92),'/',$tmpURL);
    // now replace any same string in $tmpURL value to null or ''
    // and will return value like /localhost/my_website/ or just /my_website/
    $tmpURL = str_replace($_SERVER['DOCUMENT_ROOT'],'',$tmpURL);
    // delete any slash character in first and last of value
    $tmpURL = ltrim($tmpURL,'/');
    $tmpURL = rtrim($tmpURL, '/');
    // check again if we find any slash string in value then we can assume its local machine
    if (strpos($tmpURL,'/')){
    // explode that value and take only first value
        $tmpURL = explode('/',$tmpURL);
        $tmpURL = $tmpURL[0];
    }
    // now last steps
    // assign protocol in first value
    if ($tmpURL !== $_SERVER['HTTP_HOST'])
    // if protocol its http then like this
        $base_url .= $_SERVER['HTTP_HOST'].'/'.$tmpURL.'/';
    else
    // else if protocol is https
        $base_url .= $tmpURL.'/';
    // give return value
    return $base_url; 
}

function getFileTypeImage($file_type) {
    if($file_type == "image/png" || $file_type == "image/jpg" || $file_type == "image/jpeg" || $file_type == "image/gif" || $file_type == "image/bmp") {
        return "glyphicon-picture";
    } else if($file_type == "application/octet-stream" || $file_type == "video/avi") {
        return "glyphicon-facetime-video";
    } else if($file_type == "text/x-php" || $file_type == "text/plain" || $file_type == "text/html") {
        return "glyphicon-file";
    } else if($file_type == "audio/mpeg3" || $file_type == "audio/x-mpeg-3") {
        return "glyphicon-music";
    }
}

function SendMail($To, $Subject, $Message) {
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $fromEmail = 'donotreply@' . ($_SERVER['HTTP_HOST']);
    $headers .= 'From: ' . $fromEmail . "\r\n";

    try {
        mail($To, $Subject , $Message, $headers);
        return true;
    } catch(Exception $e) {
        return false;
    }
}

function get_file_mime_type( $filename, $debug = false ) {
    if ( function_exists( 'finfo_open' ) && function_exists( 'finfo_file' ) && function_exists( 'finfo_close' ) ) {
        $fileinfo = finfo_open( FILEINFO_MIME );
        $mime_type = finfo_file( $fileinfo, $filename );
        finfo_close( $fileinfo );
        
        if ( ! empty( $mime_type ) ) {
            if ( true === $debug ) {
                return array( 'mime_type' => $mime_type, 'method' => 'fileinfo' );
            }
            return $mime_type;
        }
    }
    if ( function_exists( 'mime_content_type' ) ) {
        $mime_type = mime_content_type( $filename );
        
        if ( ! empty( $mime_type ) ) {
            if ( true === $debug ) {
                return array( 'mime_type' => $mime_type, 'method' => 'mime_content_type' );
            }
            return $mime_type;
        }
    }
    
    $mime_types = array(
        'ai'      => 'application/postscript',
        'aif'     => 'audio/x-aiff',
        'aifc'    => 'audio/x-aiff',
        'aiff'    => 'audio/x-aiff',
        'asc'     => 'text/plain',
        'asf'     => 'video/x-ms-asf',
        'asx'     => 'video/x-ms-asf',
        'au'      => 'audio/basic',
        'avi'     => 'video/x-msvideo',
        'bcpio'   => 'application/x-bcpio',
        'bin'     => 'application/octet-stream',
        'bmp'     => 'image/bmp',
        'bz2'     => 'application/x-bzip2',
        'cdf'     => 'application/x-netcdf',
        'chrt'    => 'application/x-kchart',
        'class'   => 'application/octet-stream',
        'cpio'    => 'application/x-cpio',
        'cpt'     => 'application/mac-compactpro',
        'csh'     => 'application/x-csh',
        'css'     => 'text/css',
        'dcr'     => 'application/x-director',
        'dir'     => 'application/x-director',
        'djv'     => 'image/vnd.djvu',
        'djvu'    => 'image/vnd.djvu',
        'dll'     => 'application/octet-stream',
        'dms'     => 'application/octet-stream',
        'doc'     => 'application/msword',
        'dvi'     => 'application/x-dvi',
        'dxr'     => 'application/x-director',
        'eps'     => 'application/postscript',
        'etx'     => 'text/x-setext',
        'exe'     => 'application/octet-stream',
        'ez'      => 'application/andrew-inset',
        'flv'     => 'video/x-flv',
        'gif'     => 'image/gif',
        'gtar'    => 'application/x-gtar',
        'gz'      => 'application/x-gzip',
        'hdf'     => 'application/x-hdf',
        'hqx'     => 'application/mac-binhex40',
        'htm'     => 'text/html',
        'html'    => 'text/html',
        'ice'     => 'x-conference/x-cooltalk',
        'ief'     => 'image/ief',
        'iges'    => 'model/iges',
        'igs'     => 'model/iges',
        'img'     => 'application/octet-stream',
        'iso'     => 'application/octet-stream',
        'jad'     => 'text/vnd.sun.j2me.app-descriptor',
        'jar'     => 'application/x-java-archive',
        'jnlp'    => 'application/x-java-jnlp-file',
        'jpe'     => 'image/jpeg',
        'jpeg'    => 'image/jpeg',
        'jpg'     => 'image/jpeg',
        'js'      => 'application/x-javascript',
        'kar'     => 'audio/midi',
        'kil'     => 'application/x-killustrator',
        'kpr'     => 'application/x-kpresenter',
        'kpt'     => 'application/x-kpresenter',
        'ksp'     => 'application/x-kspread',
        'kwd'     => 'application/x-kword',
        'kwt'     => 'application/x-kword',
        'latex'   => 'application/x-latex',
        'lha'     => 'application/octet-stream',
        'lzh'     => 'application/octet-stream',
        'm3u'     => 'audio/x-mpegurl',
        'man'     => 'application/x-troff-man',
        'me'      => 'application/x-troff-me',
        'mesh'    => 'model/mesh',
        'mid'     => 'audio/midi',
        'midi'    => 'audio/midi',
        'mif'     => 'application/vnd.mif',
        'mov'     => 'video/quicktime',
        'movie'   => 'video/x-sgi-movie',
        'mp2'     => 'audio/mpeg',
        'mp3'     => 'audio/mpeg',
        'mpe'     => 'video/mpeg',
        'mpeg'    => 'video/mpeg',
        'mpg'     => 'video/mpeg',
        'mpga'    => 'audio/mpeg',
        'ms'      => 'application/x-troff-ms',
        'msh'     => 'model/mesh',
        'mxu'     => 'video/vnd.mpegurl',
        'nc'      => 'application/x-netcdf',
        'odb'     => 'application/vnd.oasis.opendocument.database',
        'odc'     => 'application/vnd.oasis.opendocument.chart',
        'odf'     => 'application/vnd.oasis.opendocument.formula',
        'odg'     => 'application/vnd.oasis.opendocument.graphics',
        'odi'     => 'application/vnd.oasis.opendocument.image',
        'odm'     => 'application/vnd.oasis.opendocument.text-master',
        'odp'     => 'application/vnd.oasis.opendocument.presentation',
        'ods'     => 'application/vnd.oasis.opendocument.spreadsheet',
        'odt'     => 'application/vnd.oasis.opendocument.text',
        'ogg'     => 'application/ogg',
        'otg'     => 'application/vnd.oasis.opendocument.graphics-template',
        'oth'     => 'application/vnd.oasis.opendocument.text-web',
        'otp'     => 'application/vnd.oasis.opendocument.presentation-template',
        'ots'     => 'application/vnd.oasis.opendocument.spreadsheet-template',
        'ott'     => 'application/vnd.oasis.opendocument.text-template',
        'pbm'     => 'image/x-portable-bitmap',
        'pdb'     => 'chemical/x-pdb',
        'pdf'     => 'application/pdf',
        'pgm'     => 'image/x-portable-graymap',
        'pgn'     => 'application/x-chess-pgn',
        'png'     => 'image/png',
        'pnm'     => 'image/x-portable-anymap',
        'ppm'     => 'image/x-portable-pixmap',
        'ppt'     => 'application/vnd.ms-powerpoint',
        'ps'      => 'application/postscript',
        'qt'      => 'video/quicktime',
        'ra'      => 'audio/x-realaudio',
        'ram'     => 'audio/x-pn-realaudio',
        'ras'     => 'image/x-cmu-raster',
        'rgb'     => 'image/x-rgb',
        'rm'      => 'audio/x-pn-realaudio',
        'roff'    => 'application/x-troff',
        'rpm'     => 'application/x-rpm',
        'rtf'     => 'text/rtf',
        'rtx'     => 'text/richtext',
        'sgm'     => 'text/sgml',
        'sgml'    => 'text/sgml',
        'sh'      => 'application/x-sh',
        'shar'    => 'application/x-shar',
        'silo'    => 'model/mesh',
        'sis'     => 'application/vnd.symbian.install',
        'sit'     => 'application/x-stuffit',
        'skd'     => 'application/x-koan',
        'skm'     => 'application/x-koan',
        'skp'     => 'application/x-koan',
        'skt'     => 'application/x-koan',
        'smi'     => 'application/smil',
        'smil'    => 'application/smil',
        'snd'     => 'audio/basic',
        'so'      => 'application/octet-stream',
        'spl'     => 'application/x-futuresplash',
        'src'     => 'application/x-wais-source',
        'stc'     => 'application/vnd.sun.xml.calc.template',
        'std'     => 'application/vnd.sun.xml.draw.template',
        'sti'     => 'application/vnd.sun.xml.impress.template',
        'stw'     => 'application/vnd.sun.xml.writer.template',
        'sv4cpio' => 'application/x-sv4cpio',
        'sv4crc'  => 'application/x-sv4crc',
        'swf'     => 'application/x-shockwave-flash',
        'sxc'     => 'application/vnd.sun.xml.calc',
        'sxd'     => 'application/vnd.sun.xml.draw',
        'sxg'     => 'application/vnd.sun.xml.writer.global',
        'sxi'     => 'application/vnd.sun.xml.impress',
        'sxm'     => 'application/vnd.sun.xml.math',
        'sxw'     => 'application/vnd.sun.xml.writer',
        't'       => 'application/x-troff',
        'tar'     => 'application/x-tar',
        'tcl'     => 'application/x-tcl',
        'tex'     => 'application/x-tex',
        'texi'    => 'application/x-texinfo',
        'texinfo' => 'application/x-texinfo',
        'tgz'     => 'application/x-gzip',
        'tif'     => 'image/tiff',
        'tiff'    => 'image/tiff',
        'torrent' => 'application/x-bittorrent',
        'tr'      => 'application/x-troff',
        'tsv'     => 'text/tab-separated-values',
        'txt'     => 'text/plain',
        'ustar'   => 'application/x-ustar',
        'vcd'     => 'application/x-cdlink',
        'vrml'    => 'model/vrml',
        'wav'     => 'audio/x-wav',
        'wax'     => 'audio/x-ms-wax',
        'wbmp'    => 'image/vnd.wap.wbmp',
        'wbxml'   => 'application/vnd.wap.wbxml',
        'wm'      => 'video/x-ms-wm',
        'wma'     => 'audio/x-ms-wma',
        'wml'     => 'text/vnd.wap.wml',
        'wmlc'    => 'application/vnd.wap.wmlc',
        'wmls'    => 'text/vnd.wap.wmlscript',
        'wmlsc'   => 'application/vnd.wap.wmlscriptc',
        'wmv'     => 'video/x-ms-wmv',
        'wmx'     => 'video/x-ms-wmx',
        'wrl'     => 'model/vrml',
        'wvx'     => 'video/x-ms-wvx',
        'xbm'     => 'image/x-xbitmap',
        'xht'     => 'application/xhtml+xml',
        'xhtml'   => 'application/xhtml+xml',
        'xls'     => 'application/vnd.ms-excel',
        'xml'     => 'text/xml',
        'xpm'     => 'image/x-xpixmap',
        'xsl'     => 'text/xml',
        'xwd'     => 'image/x-xwindowdump',
        'xyz'     => 'chemical/x-xyz',
        'zip'     => 'application/zip'
    );

    $ext = strtolower( array_pop( explode( '.', $filename ) ) );
    
    if ( ! empty( $mime_types[$ext] ) ) {
        if ( true === $debug ) {
            return array( 'mime_type' => $mime_types[$ext], 'method' => 'from_array' );
        }
        return $mime_types[$ext];
    }
    
    if ( true === $debug ) {
        return array( 'mime_type' => 'application/octet-stream', 'method' => 'last_resort' );
    }
    return 'application/octet-stream';
}
?>