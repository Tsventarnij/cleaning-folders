<?php
require './App/DirectoryCleaner.php';
use App\DirectoryCleaner;

/**
 * Cleaning folders
 *
 * Delete files with BAK extension, that not have origin file
 *
 * type in console to use
 *
 * php index.php /directory/name/to/clean
 *
 * if you want to delete empty folders to:
 *
 * php index.php /directory/name/to/clean --clean
 */
$dirRoot = $argv[1];
$cleanEmptyDir = !empty($argv[2]) && $argv[2] == "--clean";

if (!empty($dirRoot) && is_readable($dirRoot)) {
    $directoryCleaner = new DirectoryCleaner($dirRoot, $cleanEmptyDir);
    $directoryCleaner->run();
} else {
    echo "Patch not found\n";
}



