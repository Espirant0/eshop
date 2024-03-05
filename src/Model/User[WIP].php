<?php

namespace App\Model;

use App\Model\Rule;

class User
{
	private string $telephone;
	private string $name;
	private string $address;
	private string $role;
	private string $password;

	public function __construct(string $telephone, string $name, string $address, string $role, string $password)
	{
		$this->telephone = $telephone;
		$this->name = $name;
		$this->address = $address;
		$this->role = $role;
		$this->password = $password;
	}

	public function getTelephone(): string
	{
		return $this->telephone;
	}

	public function setTelephone(string $telephone): void
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

	public function getAddress(): string
	{
		return $this->address;
	}

	public function setAddress(string $address): void
	{
		$this->address = $address;
	}

	public function getRole(): string
	{
		return $this->role;
	}

	public function setRole(string $role): void
	{
		$this->role = $role;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	public static function getRulesValidationUser(): Rule
	{
		return (new Rule())
			->addRule(['name'], 'min_optional:3')
			->addRule(['address'], 'min_optional:3')
			->addRule(['role_id'], ['required', 'numeric'])
			->addRule(['login'], 'min_optional:10');
	}
}