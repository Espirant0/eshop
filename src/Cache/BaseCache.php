<?php

namespace App\Cache;

abstract class BaseCache
{
	abstract public function set(string $key, $value, int $ttl = 60): void;

	abstract public function get(string $key);

	public function remember(string $key, int $ttl, \Closure $fetcher)
	{
		$data = $this->get($key);

		if ($data === null)
		{
			$value = $fetcher();
			$this->set($key, $value, $ttl);
			return $value;
		}

		return $data;
	}
}