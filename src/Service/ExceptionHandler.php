<?php

namespace App\Service;

use App\Controller\ErrorController;
use App\Controller\PageNotFoundController;

class ExceptionHandler
{
	private static ExceptionHandler $instance;

	public static function getInstance(): ExceptionHandler
	{
		if (!isset(self::$instance))
		{
			self::$instance = new ExceptionHandler();
		}
		return self::$instance;
	}

	public function errorPageRedirect(): void
	{
		http_response_code(500);
		$err = new ErrorController();
		$err->showErrorPage();
	}

	public static function tryCatch(callable $try): void
	{
		restore_error_handler();
		restore_exception_handler();
		try
		{
			$try();
		} catch (\Exception|\Error $exc)
		{
			set_error_handler([ExceptionHandler::getInstance(), 'errorToLogger']);
			set_exception_handler([ExceptionHandler::getInstance(), 'exceptionToLogger']);
			if (str_contains(get_class($exc), 'Exception'))
			{
				self::getInstance()->ExceptionToLogger($exc, 'TryCatch:Exception');
			} else
			{
				self::getInstance()->errorToLogger(type: 'TryCatch:Error', obj: $exc);
			}
		} finally
		{
			set_error_handler([ExceptionHandler::getInstance(), 'errorToLogger']);
			set_exception_handler([ExceptionHandler::getInstance(), 'exceptionToLogger']);
		}
	}

	public function errorToLogger(int $errno = 0, string $errstr = '', string $errfile = '', ?int $errline = null, ?array $errcontext = null, $type = 'Error', ?\Error $obj = null): void
	{
		$trace = '';
		if ($obj != null)
		{
			$errno = $obj->getCode();
			$errstr = $obj->getMessage();
			$errfile = $obj->getFile();
			$errline = $obj->getLine();
			$trace = $obj->getTraceAsString();
		}
		$exc = new \ErrorException($errstr, $errno, 1, $errfile, $errline);
		Logger::writeErrorToLog($exc, $trace, $type);
		self::errorPageRedirect();
	}

	public function exceptionToLogger(\Throwable $exception, $type = 'Exception'): void
	{
		$exc = new \ErrorException($exception->getMessage(), $exception->getCode(), 1, $exception->getFile(), $exception->getLine());
		$trace = $exc->getTraceAsString();
		Logger::writeErrorToLog($exc, $trace, $type);
		if ($exc->getCode() == -1)
		{
			http_response_code(404);
			$err = new PageNotFoundController();
			$err->PageNotFoundViewer();
		} else self::errorPageRedirect();
	}
}