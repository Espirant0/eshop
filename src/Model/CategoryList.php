<?php

namespace App\Model;

class CategoryList
{
	/**
	 * @var $categories category[]
	 */
	private array $categories;

	/**
	 * @param $categories category[]
	 */
	#public function __construct(array $categories)
	#{
	#	$this->categories = $categories;
	#}

	/**
	 * @return category[]
	 */
	public function getCategories(): array
	{
		return $this->categories;
	}

	/**
	 * @param $categories category[]
	 * @return void
	 */
	public function setCategories(array $categories): void
	{
		$this->categories = $categories;
	}

	/**
	 * @param Category $category
	 * @return void
	 */
	public function addCategory(Category $category):void
	{
		$this->categories[] = $category;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function removeCategoryByName(string $name):void
	{
		foreach ($this->categories as $category)
		{
			if($category->getName() === $name)
			{
				unset($this->categories[$name]);
				break;
			}
		}
	}

	/**
	 * @param string $ID
	 * @return void
	 */
	public function removeCategoryByID(string $ID):void
	{
		foreach ($this->categories as $category)
		{
			if($category->getID() === $ID)
			{
				unset($this->categories[$ID]);
				break;
			}
		}
	}
}
