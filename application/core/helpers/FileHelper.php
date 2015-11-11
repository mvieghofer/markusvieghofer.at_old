<?php
require_once(realpath(dirname(__FILE__) . "/../../../resources/config.php"));
class FileHelper {

    private static $allowedFileTypes = ['jpg' => true, 'png' => true];

    public static function isValidFile($string) {
        $fileParts = explode('.', $string);
        $fileExtension = array_pop($fileParts);
        if (isset(FileHelper::$allowedFileTypes[strtolower($fileExtension)])) {
            return true;
        }
        return false;
    }

    public static function getImportedFiles() {
        $images = [];
        $mysqli = new mysqli(Config::$db['host'], Config::$db['user'], Config::$db['password'], Config::$db['schema'], Config::$db['port']);
        if ($mysqli->connect_errno) {
            die();
        }
        $files = $mysqli->query("
                    SELECT i.parent_id as parentId, i.path as imagePath,
                    i.width as imageWidth, i.height as imageHeight,
                    i2.path as thumbnailPath, i2.width as thumbnailWidth,
                    i2.height as thumbnailHeight
                    FROM images i INNER JOIN images i2 ON i.parent_id = i2.parent_id
                    WHERE i.type = 2
                    AND i2.type = 1");
        $files->data_seek(0);
        while ($row = $files->fetch_assoc()) {
            $imageData = [];
            $imageData['image'] = [
                'path' => $row['imagePath'],
                'width' => $row['imageWidth'],
                'height' => $row['imageHeight'],
                'id' => $row['parentId']
            ];
            $imageData['thumbnail'] = [
                'path' => $row['thumbnailPath'],
                'width' => $row['thumbnailWidth'],
                'height' => $row['thumbnailHeight'],
                'parentId' => $row['parentId']
            ];
            $images[] = $imageData;
        }
        return $images;
    }

    public static function getMobileImages() {
        $images = [];
        $files =  Image::where('type', '=', '2')
                            ->get();
        foreach ($files as $file) {
            if (FileHelper::isValidFile($file->path)) {
                $imageData[] = [
                    'path' => $file->path,
                    'width' => $file->width,
                    'height' => $file->height,
                    'parentId' => $file->parent_id
                ];
                $images[] = $imageData;
            }
        }
        return $images;
    }

    public static function getFilesToImport() {
        $files = scandir(Config::$imageDirectory);
        $images = [];
        foreach ($files as $file) {
            if (FileHelper::isValidFile($file)) {
                $importedImage = Image::where('path', '=', IMAGES_PATH . '/' . $file)->get();
                if ($importedImage->isEmpty()) {
                    $image = new Image();
                    $image->path = IMAGES_PATH . '/' . $file;
                    $images[] = $image;
                } else  {
                    $image = $importedImage->first();
                    if (!$image->isActive) {
                        $images[] = $importedImage->first();
                    }
                }
            }
        }
        return $images;
    }

    public static function deleteFile($path) {
        echo "delete file $path<br />";
        Image::where('path', '=', $path)->delete();
        $fullPath = realpath(dirname(__FILE__) . '/../../public/' . $path);
        if (is_writable($fullPath)) {
            unlink($fullPath);
        }
    }
}
?>
