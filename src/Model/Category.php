<?php

namespace App\Model;

class Category
{
	private string $ID;
	private string $name;

	public function __construct(string $ID, string $name)
	{
		$this->ID = $ID;
		$this->name = $name;
	}
	public function getID():string
	{
		return $this->ID;
	}
	public function setID(string $ID):void
	{
		$this->ID = $ID;
	}
	public function getName():string
	{
		return $this->name;
	}
	public function setName(string $name):void
	{
		$this->name = $name;
	}
}

