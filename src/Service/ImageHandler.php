<?php
namespace App\Service;
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
		$files = scandir(ROOT. '/public/resources/product/img/'.$id.'.'.$itemTitle);
		$files = array_diff($files, array('.', '..'));
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

	public static function createNewItemImage(int|string $id, string $title):void
	{
		mkdir(ROOT . "/public/resources/product/img/{$id}.{$title}", 0777,true);
		copy(ROOT.'/public/resources/img/item.jpg',ROOT."/public/resources/product/img/{$id}.{$title}/{$title}_1.jpg");
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
}