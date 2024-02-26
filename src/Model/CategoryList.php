<?php

namespace App\Model;

class CategoryList implements \Iterator
{
	/**
	 * @var $data Category[]
	 */
	private array $data = array();
	private int $position = 0;

	public function __construct($data = [])
	{
		$this->data = $data;
		$this->position = 0;
	}

	public function current(): Category
	{
		return $this->data[$this->position];
	}

	public function key(): int
	{
		return $this->position;
	}

	public function next(): void
	{
		$this->position++;
	}

	public function rewind(): void
	{
		$this->position = 0;
	}

	public function valid(): bool
	{
		return isset($this->data[$this->position]);
	}

	/**
	 * @param $categories category[]
	 * @return void
	 */
	public function setCategories(array $categories): void
	{
		$this->data = $categories;
	}

	/**
	 * @param Category $category
	 * @return void
	 */
	public function addCategory(Category $category): void
	{
		$this->data[] = $category;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function removeCategoryByName(string $name): void
	{
		foreach ($this->data as $category)
		{
			if ($category->getName() === $name)
			{
				unset($this->data[$name]);
				break;
			}
		}
	}

	/**
	 * @param string $id
	 * @return void
	 */
	public function removeCategoryById(string $id): void
	{
		foreach ($this->data as $category)
		{
			if ($category->getId() === $id)
			{
				unset($this->data[$id]);
				break;
			}
		}
	}
}