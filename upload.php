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

if (!strlen($_SERVER['HTTP_X_FILE_NAME'])) {
    // classic upload
    foreach($_FILES as $file) {
        $destfile =  $file['name'];
        checkVar( $destfile );
        $target =  buildPath($BASE_PATH, $destfile);
        if (move_uploaded_file($file['tmp_name'], $target))
        $success = true;
    }
} else {
    // HTML5 single file upload
    $destfile =  $_SERVER['HTTP_X_FILE_NAME'];
    checkVar( $destfile );
    $target =  buildPath($BASE_PATH, $destfile);
    file_put_contents($target, file_get_contents("php://input"));
    $success = true;
}

print jsonResponse($success);

?>