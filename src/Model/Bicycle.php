<?php

namespace App\Model;

class Bicycle
{
	private string $id;
	private string $name = '';
	private string $color;
	private string $year;
	private string $price;
	private string $description;
	private string $status;
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
	public function __construct(
        string $id,
        string $name,
        string $color,
        string $year,
        string $material,
        string $price,
        string $description,
        string $status,
        string $vendor
    )
    {
		$this->id = $id;
		$this->name = $name;
		$this->color = $color;
		$this->year = $year;
		$this->material = $material;
		$this->price = $price;
		$this->description = $description;
		$this->status = $status;
		$this->vendor = $vendor;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function setId(string $id): void
	{
		$this->id = $id;
	}

	public function getColor(): string
	{
		return $this->color;
	}

	public function setColor(string $color): void
	{
		$this->color = $color;
	}

	public function getYear(): string
	{
		return $this->year;
	}

	public function setYear(string $year): void
	{
		$this->year = $year;
	}

	public function getPrice(): string
	{
		return $this->price;
	}

	public function setPrice(string $price): void
	{
		$this->price = $price;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function setDescription(string $description): void
	{
		$this->description = $description;
	}

	public function getStatus(): string
	{
		return $this->status;
	}

	public function setStatus(string $status): void
	{
		$this->status = $status;
	}

	public function getName():string
	{
		return $this->name;
	}

	public function setName(string $name):void
	{
		$this->name = $name;
	}

	public function getSpeed():string
	{
		return $this->speed;
	}

	public function setSpeed(string $speed):void
	{
		$this->speed = $speed;
	}

	public function getMaterial():string
	{
		return $this->material;
	}

	public function setMaterial(string $material):void
	{
		$this->material = $material;
	}

	public function getVendor():string
	{
		return $this->vendor;
	}

	public function setVendor(string $vendor):void
	{
		$this->vendor = $vendor;
	}

	public function getCategories():array
	{
		return $this->categories;
	}

	public function setCategories(array $categories):void
	{
		$this->categories = $categories;
	}
}