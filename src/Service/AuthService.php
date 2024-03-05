<?php

namespace App\Service;

class AuthService
{
	public static function checkAuth(): bool
	{
		ExceptionHandler::tryCatch(function () {
			session_start();
		});
		if (!isset($_SESSION['USER']))
		{
			return false;
		}
		return true;
	}
}