<?php

namespace App\Model;

use Core\Validator\Rules;

class Category
{
	private string $ID;
	private string $name;
	private string $engName;

	public function __construct(string $ID, string $name, string $engName)
	{
		$this->ID = $ID;
		$this->name = $name;
		$this->engName = $engName;
	}

	public function getID(): string
	{
		return $this->ID;
	}

	public function setID(string $ID): void
	{
		$this->ID = $ID;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getEngName(): ?string
	{
		return $this->engName;
	}

	public function setEngName(?string $engName): void
	{
		$this->engName = $engName;
	}

	public static function getRulesValidationCategory(): Rules
	{
		return (new Rules())
			->addRule(['engName', 'name'], 'min_optional:3');
	}

	public static function getRulesValidationVendor(): Rules
	{
		return (new Rules())
			->addRule(['name'], 'min_optional:3');
	}
}
