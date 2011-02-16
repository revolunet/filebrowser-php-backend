<?
// general purposes functions



function getDirListing( $inDir ) {
    // returns a JSON sirectory listing
    $index = 0;
    $files = array();
    $hd = opendir(  $inDir  );
    while ($file = readdir($hd)) {
        if ($file != '.' and $file != '..') {
            $files[$index]['text'] = $file;
            $files[$index]['lastmod'] = 1272391250000;
            if (!is_dir($inDir .DIRECTORY_SEPARATOR.$file)) {
                $files[$index]['leaf'] = true;
                $files[$index]['size'] = filesize($inDir .DIRECTORY_SEPARATOR.$file);
            }
            $files[$index]['iconCls'] = !is_dir($inDir .DIRECTORY_SEPARATOR.$file) ? "icon-file-".strtolower(substr(strrchr($file, '.'), 1)) : "";
            $index++;
        }
    }
    return $files;
}

    
function checkFileExtension( $inFile ) {
    // check for upload
    $allowedExtensions = array("txt","csv","htm","html","xml","css","doc","xls","rtf","ppt","pdf","swf","flv","avi","wmv","mov","jpg","jpeg","gif","png"); 
    if (!in_array(end(explode(".", strtolower($inFile))),  $allowedExtensions)) { 
       die(jsonResponse(false));
   }
}

function get_absolute_path( $path ) {
    // return absolute path from given path
    $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
        if ('.' == $part) continue;
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }
    return implode(DIRECTORY_SEPARATOR, $absolutes);
}
    
    
function checkVar( $inVar ) {
    // check variable set
    if (!isset($inVar) || $inVar=='') {
        die('error');
    }
}
 

function checkJail($dir, $jail){
    // check if $dir is below $jail
    // raise error if not
    if($dir == $jail)
        return true;
    
    $dir = get_absolute_path($dir);
    $jail = get_absolute_path($jail);
    
    $dir = count(explode(DIRECTORY_SEPARATOR, $dir));
    $jail = count(explode(DIRECTORY_SEPARATOR, $jail));
    
    if($dir <= $jail){
        die('permission denied');
    }else{
        return true;
    }
}

 
function buildPath( $root, $path ) {
    // build an absolute path from root+path and checks security
    $dst = '/' . get_absolute_path( $root . DIRECTORY_SEPARATOR . $path );
    checkJail($dst, $root );
    return $dst;
}


function jsonResponse($success = true, $msg = null) {
    $d = array(
        "success" => ($success?'true':'false')
    );
    if ($msg) {
        $d['msg'] = $msg;
    }
    return json_encode($d);
}
?>