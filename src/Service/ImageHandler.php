<?php

namespace App\Service;

use App\Config\Config;
use DeepCopy\f013\C;

class ImageHandler
{
	private $directory;

	public function __construct($directory)
	{
		$this->directory = $directory;
	}

	public static function getImageName(string $filePath): string
	{
		if (preg_match('/^.*\.png$/', $filePath))
		{
			$name = explode('.png', $filePath);
		} else
		{
			$name = explode('.jpg', $filePath);
		}
		return $name[0];
	}

	public static function getAllImageNamesForItemByTitleAndId(int|string $id, string $itemTitle): array
	{
		$files = [];
		if (file_exists(ROOT . '/public/resources/product/img/' . $id . '.' . $itemTitle))
		{
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
		if ($lastChar == '1')
		{
			return true;
		}
		return false;
	}

	public static function createNewItemDefaultImage(int|string $id, string $title): void
	{
		if (!mkdir($concurrentDirectory = ROOT . "/public/resources/product/img/{$id}.{$title}", 0777,
				true) && !is_dir($concurrentDirectory))
		{
			throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
		}
		copy(ROOT . '/public/resources/img/item.jpg',
			ROOT . "/public/resources/product/img/{$id}.{$title}/{$title}_1.jpg");
	}

	public static function createNewItemImage(string $image, int|string $id, string $title, int $imageNumber): void
	{
		if (!file_exists($directory = ROOT . "/public/resources/product/img/{$id}.{$title}") && !mkdir($directory, 0777,
				true) && !is_dir($directory))
		{
			throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
		}

		$imageData = file_get_contents($image);
		$resizedImageDetail = self::resizeImageFromString($imageData);
		$destinationPath = ROOT . "/public/resources/product/img/{$id}.{$title}/{$title}_{$imageNumber}.jpg";

		copy($resizedImageDetail, $destinationPath);
	}

	public static function resizeImageFromString($uploadedImageString): bool|string
	{
		$config = new Config();

		$desiredHeight = $config->option('IMAGE_DETAIL_HEIGHT');
		$desiredWidth = $config->option('IMAGE_DETAIL_WIDTH');

		$originalImage = imagecreatefromstring($uploadedImageString);

		$originalWidth = imagesx($originalImage);
		$originalHeight = imagesy($originalImage);

		$resizedImage = imagecreatetruecolor($desiredWidth, $desiredHeight);

		imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $originalWidth,
			$originalHeight);

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

	public static function renameImageForExistingItem(int|string $id, string $newTitle): void
	{
		$files = scandir(ROOT . '/public/resources/product/img/');
		$files = array_diff($files, array('.', '..'));
		foreach ($files as $file)
		{
			if ((int)explode('.', $file)[0] == (int)$id)
			{
				$oldTitle = $file;
				break;
			}
		}
		rename(ROOT . "/public/resources/product/img/$oldTitle", ROOT . "/public/resources/product/img/$id.$newTitle");
	}

	public static function performFilesArray(array $files): array
	{
		$performedFiles = [];
		foreach ($files as $key => $data)
		{
			foreach ($data as $iterator => $value)
			{
				$performedFiles[$iterator][$key] = $value;
			}
		}
		return $performedFiles;
	}

	public static function canUpload(array $files): string|bool
	{
		$files = self::performFilesArray($files);
		$config = new Config();
		$types = $config->option('IMAGE_ALLOWED_TYPES');
		$maxSize = $config->option('IMAGE_MAX_SIZE');
		$maxSizeInMb = floor($maxSize / 1024000);
		$checkResult = true;
		foreach ($files as $file)
		{
			$type = $file['type'];
			if (!in_array($type, $types))
			{
				$checkResult = 'Неверный формат файла';
				break;
			}
			if ($file['size'] > $maxSize)
			{
				$checkResult = "Файл не может весить больше $maxSizeInMb мб";
				break;
			}
		}
		return $checkResult;
	}
}