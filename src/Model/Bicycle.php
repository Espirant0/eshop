<?php

namespace App\Model;

class Bicycle
{
	private string $name = '';
	private int $speed = 1;
	private string $material = '';
	private string $vendor = '';
	private array $categories = [];

	/**
	 * @param string $name
	 * @param int $speed
	 * @param string $material
	 * @param string $vendor
	 * @param category[] $categories
	 */
	public function __construct(string $name, int $speed, string $material, string $vendor, array $categories)
	{
		$this->name = $name;
		$this->speed = $speed;
		$this->material = $material;
		$this->vendor = $vendor;
		$this->categories = $categories;
	}

	/**
	 * @return string
	 */
	public function getName():string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName(string $name):void
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getSpeed():string
	{
		return $this->speed;
	}

	/**
	 * @param string $speed
	 * @return void
	 */
	public function setSpeed(string $speed):void
	{
		$this->speed = $speed;
	}

	/**
	 * @return string
	 */
	public function getMaterial():string
	{
		return $this->material;
	}

	/**
	 * @param string $material
	 * @return void
	 */
	public function setMaterial(string $material):void
	{
		$this->material = $material;
	}

	/**
	 * @return string
	 */
	public function getVendor():string
	{
		return $this->vendor;
	}

	/**
	 * @param string $vendor
	 * @return void
	 */
	public function setVendor(string $vendor):void
	{
		$this->vendor = $vendor;
	}

	/**
	 * @return category[]
	 */
	public function getCategories():array
	{
		return $this->categories;
	}

	/**
	 * @param Category[] $categories
	 * @return void
	 */
	public function setCategories(array $categories):void
	{
		$this->categories = $categories;
	}
}