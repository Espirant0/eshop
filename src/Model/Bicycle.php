<?php

namespace App\Model;

use App\Service\ImageHandler;
use App\Model\Rule;

class Bicycle
{
	private int $id;
	private string $name = '';
	private string $color;
	private string $year;
	private string $price;
	private string $description;
	private string $status;
	private int $speed;
	private string $material = '';
	private string $vendor = '';
	private ?array $categories = [];
	private string $target;
	/**
	 * @var Image[] $images
	 */
	private array $images;

	/**
	 * @param string $name
	 * @param int $speed
	 * @param string $material
	 * @param string $vendor
	 * @param category[] $categories
	 * @var Image[] $images
	 */
	public function __construct(int $id, string $name, string $color, string $year, string $material, string $price, string $description, string $status, string $vendor, int $speed, ?array $categories, string $target)
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
		$this->speed = $speed;
		$this->categories = $categories;
		$this->target = $target;
		$images = [];
		/**
		 * @var Image[] $images
		 */
		foreach (ImageHandler::getAllImageNamesForItemByTitleAndId($this->id, $this->name) as $name)
		{
			$images[] = new Image($name, ImageHandler::imageMainCheck($name));
		}
		$this->images = $images;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function setId(int $id): void
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

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getSpeed(): string
	{
		return $this->speed;
	}

	public function setSpeed(string $speed): void
	{
		$this->speed = $speed;
	}

	public function getMaterial(): string
	{
		return $this->material;
	}

	public function setMaterial(string $material): void
	{
		$this->material = $material;
	}

	public function getVendor(): string
	{
		return $this->vendor;
	}

	public function setVendor(string $vendor): void
	{
		$this->vendor = $vendor;
	}

	public function getCategories(): array
	{
		return $this->categories;
	}

	public function setCategories(array $categories): void
	{
		$this->categories = $categories;
	}

	/**
	 * @return Image[]
	 */
	public function getImages()
	{
		return $this->images;
	}

	/**
	 * @var Image[] $imgArray
	 */
	public function getMainImageName(): string
	{
		$imgArray = $this->images;
		foreach ($imgArray as $image)
		{
			if ($image->isMain())
			{
				return $image->getName();
			}
		}
		return 'Has no image';
	}

	public function getTarget(): string
	{
		return $this->target;
	}

	public function setTarget(string $target): void
	{
		$this->target = $target;
	}

	public static function getRulesValidationItem(): Rule
	{
		return (new Rule())->addRule('price', 'numeric_optional')
			->addRule('description', 'min_optional:3')
			->addRule('create_year', 'min_optional:4')
			->addRule('title', 'required')
			->addRule('status', 'numeric_optional');
	}
}