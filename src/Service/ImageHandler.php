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

	public static function getImageName(string $filePath): string
	{
		if (preg_match('/^.*\.png$/', $filePath)) {
			$name = explode('.png', $filePath);
		} else {
			$name = explode('.jpg', $filePath);
		}
		return $name[0];
	}

	public static function getImageExtension(string $filePath): string
	{
		if (preg_match('/^.*\.png$/', $filePath)) {
			return '.png';
		} else {
			return '.jpg';
		}
	}

	public static function getAllImageNamesForItemByTitleAndId(int|string $id, string $itemTitle): array
	{
		$files = [];
		if (file_exists(ROOT . '/public/resources/product/img/' . $id . '.' . $itemTitle)) {
			$files = scandir(ROOT . '/public/resources/product/img/' . $id . '.' . $itemTitle);
			$files = array_diff($files, array('.', '..'));
		}
		return array_values($files);
	}

	public static function imageMainCheck(string $name): bool
	{
		$name = ImageHandler::getImageName($name);
		$name = explode('_', $name);
		(string)$lastChar = end($name);
		if ($lastChar == '1') {
			return true;
		}
		return false;
	}

	public static function createNewItemDefaultImage(int|string $id, string $title): void
	{
		if (!mkdir($concurrentDirectory = ROOT . "/public/resources/product/img/{$id}.{$title}", 0777, true) && !is_dir($concurrentDirectory)) {
			throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
		}
		copy(ROOT . '/public/resources/img/item.jpg', ROOT . "/public/resources/product/img/{$id}.{$title}/{$title}_1.jpg");
	}

	public static function createNewItemImage(string $image, int|string $id, string $title, int $imageNumber): void
	{
		$imageData = file_get_contents($image);
		$resizedImage = self::resizeImageFromString($imageData);

		if (!file_exists($directory = ROOT . "/public/resources/product/img/{$id}.{$title}") && !mkdir($directory, 0777, true) && !is_dir($directory)) {
			throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
		}

		$destinationPath = ROOT . "/public/resources/product/img/{$id}.{$title}/{$title}_{$imageNumber}.jpg";
		copy($resizedImage, $destinationPath);
	}

	public static function resizeImageFromString($uploadedImageString): bool|string
	{
		$config = new Config();

		$desiredWidth = 1000;
		$desiredHeight = 1000;

		$originalImage = imagecreatefromstring($uploadedImageString);

		$originalWidth = imagesx($originalImage);
		$originalHeight = imagesy($originalImage);

		$resizedImage = imagecreatetruecolor($desiredWidth, $desiredHeight);

		imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

		$tempImagePath = tempnam(sys_get_temp_dir(), 'resized_image');
		self::convertImageToJPEG($resizedImage, $tempImagePath);

		imagedestroy($originalImage);
		imagedestroy($resizedImage);

		return $tempImagePath;
	}

	public static function convertImageToJPEG($resizedImage, $resizedImagePath): void
	{
		imagejpeg($resizedImage, $resizedImagePath, 90);
	}

	public static function can_upload($file)
	{
		for ($i = 0, $iMax = count($file['name']); $i < $iMax; $i++) {
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