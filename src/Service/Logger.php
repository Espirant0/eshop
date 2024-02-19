<?php

namespace App\Service;

class Logger
{
	private static $messageLine=1;
	public static function writeErrorToLog(\ErrorException $info, string $trace = '', string $type = 'Error'):void
	{
		$errorLogPath = ROOT.'/var/logs';
		$errorLogFile = ROOT."/var/logs/".date("Y-m-d")."-latest.txt";
		$errorLogFileSize = 1024*1024;
		$logString=date('[H-i-s]-')."Type=[$type]:\nOccurrence:".$info->getMessage().";\nwith code:[".$info->getCode()."];\nat:\nFile:".$info->getFile()." [Line:".$info->getLine()."]\n";
		if(!file_exists($errorLogPath))
		{
			mkdir($errorLogPath,0777,true);
		}
		$logFiles = scandir($errorLogPath);
		$logFiles = array_diff($logFiles, array('.', '..'));
		sort($logFiles);
		if(count($logFiles)==0) file_put_contents($errorLogFile,$logString);
		foreach ($logFiles as $logFile)
		{
			if(in_array('latest.txt',explode('-',$logFile)))
			{
				if(filesize($errorLogPath.'/'.$logFile) < $errorLogFileSize && (int)explode('-',$errorLogFile)[2]<=(int)(date('d')))
				{
					file_put_contents($errorLogPath.'/'.$logFile,$logString, FILE_APPEND);
				}
				else
				{
					rename($errorLogPath.'/'.$logFile, $errorLogPath.'/'.explode('-latest',$logFile)[0].'.txt');
					file_put_contents($errorLogFile,$logString,FILE_APPEND);
				}
				break;
			}
		}
	}
	public static function writeToLog(string $message):void
	{

		$errorLogFile = ROOT."/var/logs/MESSAGELOG.txt";
		if(self::$messageLine==1)
		{
			file_put_contents($errorLogFile,"----------новый прогон---------\n", FILE_APPEND);
		}
		file_put_contents($errorLogFile,"[".self::$messageLine."]".$message."\n", FILE_APPEND);
		self::$messageLine++;
	}
}