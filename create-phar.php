<?php
$srcRoot = "../sid";
$buildRoot = "../siddeploy";

$phar = new Phar($buildRoot . "/sidphar.phar",0, "sidphar.phar");
$phar->buildFromDirectory(dirname(__FILE__) . './');
$phar->setStub($phar->createDefaultStub('index.php'));
copyr($srcRoot . "/system", $buildRoot . "/system");
copyr($srcRoot . "/recursos", $buildRoot . "/recursos");
/*
$phar2 = new Phar('project2.phar', 0, 'project2.phar');
// add all files in the project, only include php files
$phar2->buildFromDirectory(dirname(__FILE__) . '/project', '/\.php$/');
$phar2->setStub($phar->createDefaultStub('cli/index.php', 'www/index.php'));


$phar = new Phar($buildRoot . "/sidphar.phar", 
	FilesystemIterator::CURRENT_AS_FILEINFO |     	FilesystemIterator::KEY_AS_FILENAME, "sidphar.phar");
$phar["index.php"] = file_get_contents($srcRoot . "/index.php");
//$phar["common.php"] = file_get_contents($srcRoot . "/common.php");
$phar->setStub($phar->createDefaultStub("index.php"));*/
//copy($srcRoot . "/config.ini", $buildRoot . "/config.ini");


/**
 * Copy a file, or recursively copy a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function copyr($source, $dest)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        copyr("$source/$entry", "$dest/$entry");
    }

    // Clean up
    $dir->close();
    return true;
}