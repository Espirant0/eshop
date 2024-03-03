<?php

namespace App\Service;

use App\Config\Config;

class Logger
{
	private static string $errorLogPath = ROOT . '/var/logs';

	private static function createLogDirectory(?string $logDir = null): void
	{
		if ($logDir == null) $logDir = self::$errorLogPath;
		else $logDir = self::$errorLogPath . "/" . $logDir;
		if (!file_exists($logDir))
		{
			mkdir($logDir, 0777, true);
		}
	}
	public static function sizeRelatedRenaming(string $logDir):void
	{
		$logDayCap = Config::getInstance()->option('LOG_FILE_DAYS_CAP');
		$errorLogFileSize = Config::getInstance()->option('ERROR_LOG_FILE_SIZE');
		$logPath = self::$errorLogPath.'/'.$logDir;
		$logFiles = scandir($logPath);
		$logFiles = array_diff($logFiles, array('.', '..'));
		sort($logFiles);
		if (count($logFiles) == 0)
		{
			file_put_contents($logPath . '/' . date("Y-m-d") . "-latest.txt", "Error log file initialization\n");
		}
		foreach ($logFiles as $logFile)
		{
			if (in_array('latest.txt', explode('-', $logFile)))
			{
				if (filesize($logPath . '/' . $logFile) > $errorLogFileSize || abs((int)(date('d')) - (int)explode('-', $logFile)[2]) >= $logDayCap)
				{
					rename($logPath . '/' . $logFile, $logPath . '/' . explode('-latest', $logFile)[0] . '.txt');
				}
			}
		}
	}

	public static function writeErrorToLog(\ErrorException $info, string $trace = '', string $type = 'Error'): void
	{
		$errorLogFile = ROOT . "/var/logs/errorLogs/" . date("Y-m-d") . "-latest.txt";
		$logString = date('[H-i-s]-') . "Type=[$type]:\nOccurrence:" . $info->getMessage() . ";\nwith code:[" . $info->getCode() . "];\nat:\nFile:" . $info->getFile() . " [Line:" . $info->getLine() . "]\n";
		self::createLogDirectory('errorLogs');
		self::sizeRelatedRenaming('errorLogs');
		file_put_contents($errorLogFile, $logString, FILE_APPEND);
	}

	public static function ORMLogging(string $message, string $ORMfunction = 'ORM-work'): void
	{
		$errorLogFile = ROOT . "/var/logs/ORMlogs/ORM_" . date("Y-m-d") . "-latest.txt";
		self::createLogDirectory('ORMlogs');
		self::sizeRelatedRenaming('ORMlogs');
		file_put_contents($errorLogFile, date('[H-i-s] ') . $message . ' at ' . $ORMfunction . "\n", FILE_APPEND);
	}
}