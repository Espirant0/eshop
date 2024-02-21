<?php
namespace App\Service;
use App\Config\Config;

class ImageHandler
{
	private $directory;
	public function __construct($directory)
	{
		$this->directory = $directory;
	}
	public static function getImageName(string $filePath):string
	{
		if(preg_match('/^.*\.png$/', $filePath))
		{
			$name = explode('.png', $filePath);
		}
		else
		{
			$name = explode('.jpg', $filePath);
		}
		return $name[0];
	}
	public static function getImageExtension(string $filePath):string
	{
		if(preg_match('/^.*\.png$/', $filePath))
		{
			return '.png';
		}
		else
		{
			return '.jpg';
		}
	}
	public static function getAllImageNamesForItemByTitleAndId(int|string $id, string $itemTitle):array
	{
		$files = [];
		if(file_exists(ROOT. '/public/resources/product/img/'.$id.'.'.$itemTitle))
		{
			$files = scandir(ROOT . '/public/resources/product/img/' . $id . '.' . $itemTitle);
			$files = array_diff($files, array('.', '..'));
		}
		return array_values($files);
	}

	public static function imageMainCheck(string $name):bool
	{
		$name = ImageHandler::getImageName($name);
		$name = explode('_',$name);
		(string)$lastChar = end($name);
		if ($lastChar == '1')
		{
			return true;
		}
		return false;
	}

	public static function createNewItemDefaultImage(int|string $id, string $title):void
	{
		mkdir(ROOT . "/public/resources/product/img/{$id}.{$title}", 0777,true);
		copy(ROOT.'/public/resources/img/item.jpg',ROOT."/public/resources/product/img/{$id}.{$title}/{$title}_1.jpg");
	}

	public static function createNewItemImage(string $image, int|string $id, string $title, int $imageNumber):void
	{
		if(!file_exists(ROOT . "/public/resources/product/img/{$id}.{$title}")) {
			mkdir(ROOT . "/public/resources/product/img/{$id}.{$title}", 0777, true);
		}
		copy($image,ROOT."/public/resources/product/img/{$id}.{$title}/{$title}_{$imageNumber}.jpg");
	}
	public static function renameImageForExistingItem(int|string $id, string $newTitle):void
	{
		$files = scandir(ROOT. '/public/resources/product/img/');
		$files = array_diff($files, array('.', '..'));
		foreach ($files as $file)
		{
			if((int)explode('.',$file)[0]==(int)$id)
			{
				$oldTitle = $file;
				break;
			}
		}
		rename(ROOT."/public/resources/product/img/$oldTitle",ROOT."/public/resources/product/img/$id.$newTitle");
	}

    public static function resizeImage($pathToUploadedImage)
    {
        $config = new Config();

        $desiredWidth = $config->option('IMAGE_WIDTH');
        $desiredHeight = $config->option('IMAGE_HEIGHT');

        $imageInfo = getimagesize($pathToUploadedImage);
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];

        $originalImage = imagecreatefromstring(file_get_contents($pathToUploadedImage));

        $resizedImage = imagecreatetruecolor($desiredWidth, $desiredHeight);

        imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

        $resizedImagePath = $pathToUploadedImage;

        self::convertImageToJPEG($resizedImage, $resizedImagePath);

        imagedestroy($originalImage);
        imagedestroy($resizedImage);
    }

    public static function convertImageToJPEG($resizedImage, $resizedImagePath): void
    {
        imagejpeg($resizedImage, $resizedImagePath, 90);
    }

	public static function can_upload($file)
	{
		for ($i = 0; $i < count($file['name']); $i++) {
			$getMime = explode('.', $file['name'][$i]);
			$mime = strtolower(end($getMime));
			$types = ['jpg', 'png', 'gif', 'bmp', 'jpeg'];
			if (!in_array($mime, $types)) {
				return false;
			}
			return true;
		}
	}
}