<?php

namespace App\Model;

class Image
{
	private string $name;
	private bool $isMain;

	public function __construct($name, $isMain = false)
	{
		$this->name = $name;
		$this->isMain = $isMain;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function isMain(): bool
	{
		return $this->isMain;
	}

	public function setStatus(bool $status): void
	{
		$this->isMain = $status;
	}
}