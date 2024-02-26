<?php

namespace App\Service;

use Core\Database\Migration\Migrator;

class ClearTestData
{
	public static function clear(): void
	{
		$standart = ['1.Ortler E-Montana 400', '10.Author Arsenal', '11.Author Mistral', '12.Keltt Street One', '13.Keltt Fieria', '14.Keltt Meteora', '15.STINGER VERONA', '16.Keltt Compact 2021', '17.Keltt Compact 2024', '18.Forward Bizon', '19.Velopro ML150L', '2.Specialized Turbo Vado 4.0 E-Bike', '20.Keltt Raptor', '21.Author A-Matrix', '22.Keltt Corsair', '23.Author Melody', '24.Novatrack 16 Tetris', '25.Keltt Rocket', '26.Author Smart 20', '3.Giant 27.5 Dirt-E+', '4.Bulls Aminga Eva 1 CX', '5.BMX TechTeam Goof', '6.TM Alubike XTA', '7.Velopro ML150', '8.Trinx M136 Elite', '9.Trinx M100 Pro'];
		echo "Clearing database\n";
		Migrator::deleteData();
		echo "Apply migrations for beginning\n";
		Migrator::migrate();
		$files = scandir(ROOT . '/public/resources/product/img/');
		$files = array_diff($files, array('.', '..'));
		$counted = count($files) - 26;
		echo 'Found ' . $counted . " directories to delete\n";
		$countedDir = 0;
		$countedFiles = 0;
		foreach ($files as $file)
		{
			if ((int)explode('.', $file)[0] > 26)
			{
				$innerFiles = scandir(ROOT . "/public/resources/product/img/{$file}");
				$innerFiles = array_diff($innerFiles, array('.', '..'));
				foreach ($innerFiles as $innerFile)
				{
					unlink(ROOT . "/public/resources/product/img/{$file}/$innerFile");
					$countedFiles++;
				}
				rmdir(ROOT . "/public/resources/product/img/{$file}");
				$countedDir++;
			}
		}
		$renamedDirs = 0;
		foreach ($files as $file)
		{
			foreach ($standart as $standartName)
			{
				if ((int)explode('.', $file)[0] == (int)explode('.', $standartName)[0] &&
					explode('.', $file)[1] != explode('.', $standartName)[1])
				{
					rename(ROOT . "/public/resources/product/img/{$file}", ROOT . "/public/resources/product/img/{$standartName}");
					$renamedDirs++;
				}
			}
		}
		echo 'Clearing data completed with ' . $countedDir . ' deleted directories, ' . $countedFiles . ' deleted files and ' . $renamedDirs . ' renamed dirs';
	}
}