<?php

namespace App\Service;

class AuthService
{
	public static function checkAuth():bool
	{
		session_start();
		if(!isset($_SESSION['USER']))
		{
			return false;
		}
		return true;
	}
}