<?php
//
// a simple PHP filebrowser API
// 
// there is an hardcoded $BASE_PATH that should be the jail 
// all commands are relative to this root
// the upload commands are located in upload.php
//
// commands :
//
//    * GET
//        * cmd=view&file=path/to/file.jpg  : diplay/download arbitraty file
//
//    * POST
//        * cmd=get&path=a/nother/path : directory listing
//        * cmd=newdir&dir=a/nother/path : creates a directory
//        * cmd=rename&oldname=path/to/old.txt&newname=path/to/new.jpg : renames a file or directory
//        * cmd=delete&file=a/nother/file.jpeg : deletes a file or directory
//

header("Content-Type:text/javascript");

require('utils.php');
require('config.php');


// set to GET for testing purposes, should be POST by default (security)
$DATAS=$_POST;

$xaction = $DATAS['cmd'];

if (isset($_GET['cmd'])) {
    if ($_GET['cmd']=='view' || $_GET['cmd']=='upload' ) {
        // special case for cmd=view which always uses GET (view/download)
        // special case for upload (cannot send in POST along with HTML5 D'n'd
        $xaction = $_GET['cmd'];
        }
}

// the buildPath function is responsible of security

switch ($xaction) {
    case 'get':
        // get directory listing
        $destfile =  $DATAS['path'];
        $target =  buildPath($BASE_PATH, $destfile); 
       // print $target;
        if (!is_dir(  $target )) {
            $json = array();
        }
        else {
            $json = getDirListing( $target );
        }
        print json_encode($json);
        break;
    case 'newdir':
        // create new dir
        $destfile =  $DATAS['dir'];
        checkVar( $destfile );
        $target =  buildPath($BASE_PATH, $destfile); 
        if (!@mkdir( $target )) {
            print jsonResponse(false, 'cannot create directory');
            break;
        }
        print jsonResponse();
        break;
    case 'rename':
        // rename file/dir
        $old = buildPath($BASE_PATH, $DATAS['oldname']); 
        $new = buildPath($BASE_PATH, $DATAS['newname']); 
        if (!@rename( $old, $new)) {
            print jsonResponse(false, 'cannot rename');
            break;
        }
        print jsonResponse();
        break;
    case 'delete':
        // delete file/dir
        $destfile =  $DATAS['file'];
        checkVar( $destfile );
        $target =  buildPath($BASE_PATH, $destfile); 
        if (!file_exists( $target )) die('Fichier inexistant');
        if (is_dir(  $target ))
            if (!@rmdir(  $target )) {
                print jsonResponse(false, 'cannot delete');
                break;
            }
        else if (file_exists(  $target ))
            if (!@unlink(  $target )) {
                print jsonResponse(false, 'cannot delete');
                break;
            }
        print jsonResponse(file_exists( $target )?false:true);
        break;
    case 'view':
        // download target file (using GET)
        $destfile =  $_GET['file'];
        checkVar( $destfile );
        $target =  buildPath($BASE_PATH, $destfile); 
        if (!file_exists( $target )) {
            print jsonResponse(false, 'file does not exists');
            break;
         }
        $hd = fopen($target, "rb");
        $ext = strtolower(substr(strrchr($destfile, '.'), 1));
        header('Content-Disposition: inline; filename="'. $destfile . '"');
        header ('Content-type: image/'.$ext);
        header('Content-Length: ' . filesize($target)); 
        while (!feof($hd)) {
            print fread($hd, 4096); 
            flush();
        }
        fclose($hd);
        break;
    case 'upload':
        if (!strlen($_SERVER['HTTP_X_FILE_NAME'])) {
            // classic upload
            foreach($_FILES as $file) {
                $destfile =  $file['name'];
                checkVar( $destfile );
                $target =  buildPath($BASE_PATH, $destfile);
                checkFileExtension( $target );
                if (move_uploaded_file($file['tmp_name'], $target)) $success = true;
            }
        } else {
            // HTML5 single file upload
            $destfile =  $_SERVER['HTTP_X_FILE_NAME'];
            checkVar( $destfile );
            $target =  buildPath($BASE_PATH, $destfile);
            checkFileExtension( $target );
            if (!@file_put_contents($target, file_get_contents("php://input"))) {
                print jsonResponse(false, 'cannot create file');
                break;
            }
            $success = true;
        }

        print jsonResponse($success);
        break;
}
  
?>