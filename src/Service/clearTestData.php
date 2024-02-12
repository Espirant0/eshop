<?php

namespace App\Service;
use Core\Database\Migration\Migrator;

class clearTestData
{
	public static function clear():void
	{
		echo "Clearing database\n";
		Migrator::deleteData();
		echo "Apply migrations for beginning\n";
		Migrator::migrate();
		$files = scandir(ROOT. '/public/resources/product/img/');
		$files = array_diff($files, array('.', '..'));
		$counted = count($files)-26;
		echo 'Found '.$counted." directories to delete\n";
		$countedDir=-26;
		$countedFiles=0;
		foreach ($files as $file)
		{
			if((int)explode('.',$file)[0]>26)
			{
				$innerFiles=scandir(ROOT. "/public/resources/product/img/{$file}");
				$innerFiles = array_diff($innerFiles, array('.', '..'));
				foreach ($innerFiles as $innerFile)
				{
					unlink(ROOT."/public/resources/product/img/{$file}/$innerFile");
					$countedFiles++;
				}
				rmdir(ROOT. "/public/resources/product/img/{$file}");
			}
			$countedDir++;
		}
		echo 'Clearing data completed with '.$countedDir.' deleted directories and '.$countedFiles.' deleted files';
	}
}