<?php

namespace App\Config;

class Config
{
	private static Config $instance;
	public static function getInstance(): Config
	{
		if (!isset(self::$instance))
		{
			self::$instance = new self;
		}
		return self::$instance;
	}
	public function option(string $name, $defaultValue = null)
	{
		/** @var array $config */
		static $config = null;

		if ($config === null)
		{
			$masterConfig = require ROOT . '/config/config.php';

			if (file_exists(ROOT . '/config/config.local.php'))
			{
				$localConfig = require ROOT . '/config/config.local.php';
			} else
			{
				$localConfig = [];
			}

			$config = array_merge($masterConfig, $localConfig);
		}

		if (array_key_exists($name, $config))
		{
			return $config[$name];
		}

		if ($defaultValue !== null)
		{
			return $defaultValue;
		}

		throw new \Exception("Configuration option $name not found");
	}
}