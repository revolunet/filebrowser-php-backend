<?
//
// simple upload script for FileBrowser
//
// two ways of upload : 
//   * HTML5 drag'n'drop (HTTP_X_FILE_NAME) -> single file
//   * classic $_FILES -> N files
//

header("Content-Type:text/javascript");

require('utils.php');
require('config.php');

$success = false;


    
function checkFileExtension( $inFile ) {
    $allowedExtensions = array("txt","csv","htm","html","xml","css","doc","xls","rtf","ppt","pdf","swf","flv","avi","wmv","mov","jpg","jpeg","gif","png"); 
    if (!in_array(end(explode(".", strtolower($inFile))),  $allowedExtensions)) { 
       die(jsonResponse(false));
   }
}
// du upload stuff

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

?>