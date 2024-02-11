<?php

namespace App\Cache;

class FileCache extends BaseCache
{
    public function set(string $key, $value, int $ttl = 60): void
    {
        $hash = sha1($key);
		if(!is_dir(ROOT . '/var/cache'))
		{
			mkdir(ROOT . '/var/cache',0777,true);
		}
        $path = ROOT . '/var/cache/' . $hash . '.php';

        $data = [
            'data' => $value,
            'ttl' => time() + $ttl,
        ];

        file_put_contents($path, serialize($data));
    }

    public function get(string $key)
    {
        $hash = sha1($key);
        $path = ROOT . '/var/cache/' . $hash . '.php';

        if (!file_exists($path))
        {
            return null;
        }

        $data = unserialize(file_get_contents($path), ['allowed_classes' => true]);

        $ttl = $data['ttl'];

        if (time() > $ttl)
        {
            return null;
        }

        return $data['data'];
    }

    //TODO: deleteCache
}