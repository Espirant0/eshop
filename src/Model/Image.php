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

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */

	/**
	 * @return bool
	 */
	public function isMain(): bool
	{
		return $this->isMain;
	}

	/**
	 * @param bool $isMain
	 */
	public function setStatus(bool $status): void
	{
		$this->isMain = $status;
	}
}