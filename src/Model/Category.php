<?php

namespace App\Model;

use Core\Validator\Rules;

class Category
{
	private string $id;
	private string $name;
	private string $engName;

	public function __construct(string $id, string $name, string $engName)
	{
		$this->id = $id;
		$this->name = $name;
		$this->engName = $engName;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function setId(string $id): void
	{
		$this->id = $id;
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
