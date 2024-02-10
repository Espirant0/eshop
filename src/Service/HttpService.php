<?php

namespace App\Service;

class HttpService
{
	public static function redirect(string $url): void
	{
		header("Location: /$url");
	}
}