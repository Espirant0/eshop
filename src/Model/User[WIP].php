<?php

namespace App\Model;

class User
{
	private string $telephone;
	private string $name;
	private string $address;
	private string $role;

	public function __construct(string $telephone, string $name, string $address, string $role)
	{
		$this->telephone = $telephone;
		$this->name = $name;
		$this->address = $address;
		$this->role = $role;
	}

	public function getTelephone():string
	{
		return $this->telephone;
	}

	public function setTelephone(string $telephone):void
	{
		$this->telephone = $telephone;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getAddress():string
	{
		return $this->address;
	}

	public function setAddress(string $address):void
	{
		$this->address = $address;
	}

	public function getRole():string
	{
		return $this->role;
	}

	public function setRole(string $role):void
	{
		$this->role = $role;
	}
}

