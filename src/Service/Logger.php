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
	private static function sizeRelatedRenaming(string $logDir):void
	{
		$logDayCap = Config::getInstance()->option('LOG_FILE_DAYS_CAP');
		$errorLogFileSize = Config::getInstance()->option('ERROR_LOG_FILE_SIZE');
		$logPath = self::$errorLogPath.'/'.$logDir;
		$logFiles = scandir($logPath);
		$logFiles = array_diff($logFiles, array('.', '..'));
		sort($logFiles);
		foreach ($logFiles as $logFile)
		{
			if (in_array('latest.txt', explode('-', $logFile)))
			{
				if (filesize($logPath . '/' . $logFile) > $errorLogFileSize
					|| abs((int)(date('d')) - (int)explode('-', $logFile)[2]) >= $logDayCap)
				{
					rename($logPath . '/' . $logFile, $logPath . '/' . explode('-latest', $logFile)[0] . '.txt');
				}
			}
		}
	}

	private static function getLatestLogFileInLogDir(string $logDir):string
	{
		$logPath = self::$errorLogPath.'/'.$logDir;
		$logFiles = scandir($logPath);
		$logFiles = array_diff($logFiles, array('.', '..'));
		foreach ($logFiles as $logFile)
		{
			if (in_array('latest.txt', explode('-', $logFile)))
			{
				return $logFile;
			}
		}
		return date("Y-m-d") . "-latest.txt";
	}
	public static function writeErrorToLog(\ErrorException $info, string $trace = '', string $type = 'Error'): void
	{
		self::createLogDirectory('errorLogs');
		self::sizeRelatedRenaming('errorLogs');
		$errorLogFile = ROOT . "/var/logs/errorLogs/" . self::getLatestLogFileInLogDir('errorLogs');
		$logString = date('[H-i-s]-') . "Type=[$type]:\nOccurrence:" . $info->getMessage() . ";\nwith code:[" . $info->getCode() . "];\nat:\nFile:" . $info->getFile() . " [Line:" . $info->getLine() . "]\n";
		file_put_contents($errorLogFile, $logString, FILE_APPEND);
	}

	public static function ORMLogging(string $message, string $ORMfunction = 'ORM-work'): void
	{
		self::createLogDirectory('ORMlogs');
		self::sizeRelatedRenaming('ORMlogs');
		$logName = self::getLatestLogFileInLogDir('ORMlogs');
		if (str_contains($logName,'ORM'))
		{
			$errorLogFile = ROOT . "/var/logs/ORMlogs/$logName";
		}
		else
		{
			$errorLogFile = ROOT . "/var/logs/ORMlogs/ORM_" . self::getLatestLogFileInLogDir('ORMlogs');
		}
		file_put_contents($errorLogFile, date('[H-i-s] ') . $message . ' at ' . $ORMfunction . "\n", FILE_APPEND);
	}
}