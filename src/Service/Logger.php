<?php

namespace App\Service;

class Logger
{
	private static int $messageLine = 1;
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

	public static function writeErrorToLog(\ErrorException $info, string $trace = '', string $type = 'Error'): void
	{
		$errorLogFile = ROOT . "/var/logs/" . date("Y-m-d") . "-latest.txt";
		$errorLogFileSize = 1024 * 1024;
		$logString = date('[H-i-s]-') . "Type=[$type]:\nOccurrence:" . $info->getMessage() . ";\nwith code:[" . $info->getCode() . "];\nat:\nFile:" . $info->getFile() . " [Line:" . $info->getLine() . "]\n";
		self::createLogDirectory();
		$logFiles = scandir(self::$errorLogPath);
		$logFiles = array_diff($logFiles, array('.', '..'));
		sort($logFiles);
		if (count($logFiles) == 0) file_put_contents($errorLogFile, $logString);
		foreach ($logFiles as $logFile)
		{
			if (in_array('latest.txt', explode('-', $logFile)))
			{
				if (filesize(self::$errorLogPath . '/' . $logFile) < $errorLogFileSize && (int)explode('-', $errorLogFile)[2] <= (int)(date('d')))
				{
					file_put_contents(self::$errorLogPath . '/' . $logFile, $logString, FILE_APPEND);
				} else
				{
					rename(self::$errorLogPath . '/' . $logFile, self::$errorLogPath . '/' . explode('-latest', $logFile)[0] . '.txt');
					file_put_contents($errorLogFile, $logString, FILE_APPEND);
				}
				break;
			}
		}
	}

	public static function writeToLog(string $message): void
	{
		self::createLogDirectory();
		$errorLogFile = ROOT . "/var/logs/MESSAGELOG.txt";
		if (self::$messageLine == 1)
		{
			file_put_contents($errorLogFile, "----------новый прогон---------\n", FILE_APPEND);
		}
		file_put_contents($errorLogFile, date('[H-i-s]--') . "[" . self::$messageLine . "]" . $message . "\n", FILE_APPEND);
		self::$messageLine++;
	}

	public static function ORMLogging(string $message, string $ORMfunction = 'ORM-work'): void
	{
		$specificDir = 'ORMlogs';
		self::createLogDirectory($specificDir);
		$errorLogFile = ROOT . "/var/logs/$specificDir/ORMlog.txt";
		file_put_contents($errorLogFile, date('[H-i-s] ') . $message . ' at ' . $ORMfunction . "\n", FILE_APPEND);
	}
}