<?php

require_once dirname(__FILE__) . '/config.inc.php';
require_once dirname(__FILE__) . '/lib/php-opencloud/lib/php-opencloud.php';

// Define the path to the library
$libraryPath = dirname(__FILE__) . '/lib/php-opencloud/lib/';

// Include the autoloader
require_once $libraryPath . 'Autoload.php';

$conn = new \OpenCloud\Rackspace(
    $RSAPIURL,
    array(
        'username' => $RSUSERNAME,
        'apiKey' => $RSAPIKEY,
    ));

$ostore = $conn->ObjectStore('cloudFiles', 'DFW', 'publicURL');
$mycontainer = $ostore->Container($RSContainer);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<!-- This page was created with the build-github-pages.sh script found at 
     https://github.com/mattrude/mattrude.github.com for 
     http://gh.mattrude.com -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo "$SITEHEADER"; ?></title>
    <link rel="stylesheet" type="text/css" href="/style.css" />
    <link rel="icon" href="/favicon.ico" type="image/x-icon" />
</head>
<body>
    <div id="content"><div id="primary" class="main">
    <h1><?php echo "$SITEHEADER"; ?></h1>

<?php
if (isset($_GET['dir'])) {
    $prefix=$_GET['dir'];
    echo " <a href=\"/\"> $SITENAME</a> / <a href=\"/?dir=$prefix\">$prefix</a> / ";
    echo "<table><tr><th>Name</th><th>Size</th></tr>";
    $objlist = $mycontainer->ObjectList(array('prefix'=>$_GET['dir']));
    while($object = $objlist->Next()) {
        if ( $object->bytes > 0 ) {
            $name = $object->name;
            $namepretty = str_replace(".mp3", "", $name);
            $namepretty = str_replace($prefix . "/", "", $namepretty);
            $size=formatBytes($object->bytes);
            echo "<tr><td><a href=\"$CDNURL/$name\">$namepretty</a></td><td>$size</td></tr>";
        }
    }
    echo "</table>";
} else {
    $consuming = formatBytes($mycontainer->bytes);
    printf("This site has %s recordings consuming %d Gigabytes of drive space\n",
    $mycontainer->count, $consuming);
    echo "<ul>";
    $objlist = $mycontainer->ObjectList(array('delimiter'=>'/'));
    while($object = $objlist->Next()) {
        if ( ! $object->name == NULL ) {
            echo "<li><a href=\"/?dir=$object->name\">$object->name</a></li>";
        }
    }
    echo "</ul>";
}

echo "</div></div></body></html>";

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 
?>
