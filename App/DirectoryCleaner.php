<?php
namespace App;

use FilesystemIterator;
/**
 * Class DirectoryCleaner
 * @package App\DirectoryCleaner
 */
class DirectoryCleaner {

    private const BACKUP_EXTENSION = "BAK";

    /**
     * @var String
     */
    private $mainDirectory;
    /**
     * @var bool
     */
    private $deletingEmptyDir;

    /**
     * DirectoryCleaner constructor.
     * @param String $mainDirectory
     * @param bool $deletingEmptyDir
     */
    public function __construct(String $mainDirectory, Bool $deletingEmptyDir = false)
    {
        $this->mainDirectory = $mainDirectory;
        $this->deletingEmptyDir = $deletingEmptyDir;
    }

    /**
     * @param String $directory
     */
    private function checkBackup(String $directory) {
        $files = array_diff(scandir($directory), array('..', '.'));
        foreach ($files as $key => $file) {
            if (is_dir($directory . '/' . $file)) {
                $this->checkBackup($directory . '/' . $file);
                unset($files[$key]);
            } elseif (preg_match("/^.*\.(".self::BACKUP_EXTENSION.")$/i", $file)) {
                $fileName = substr($file, 0, - strlen(self::BACKUP_EXTENSION));
                unset($files[$key]);
                if (!$this->hasOriginFile($fileName, $files)) {
                    $this->deleteBackup($directory . "/" . $file );
                }
            }
        }
        $isDirEmpty = !(new FilesystemIterator($directory))->valid();
        if ($isDirEmpty && $this->deletingEmptyDir) {
            rmdir($directory);
        }
    }

    /**
     * @param String $fileName
     * @param array $files
     * @return bool
     */
    private function hasOriginFile(String $fileName, array $files) : Bool
    {
        foreach($files as $file) {
            if (strpos($file, $fileName) !== FALSE)
                return true;
        }
        return false;
    }


    /**
     * @param String $fileName
     */
    private function deleteBackup(String $fileName) {
        unlink($fileName);
    }

    public function run() {
        $this->checkBackup($this->mainDirectory);

    }

}