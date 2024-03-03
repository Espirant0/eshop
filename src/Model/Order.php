<?php

namespace App\Model;

use App\Model\Rule;

class Order
{
	private int $orderId;
	private string $itemName;
	private string $status;
	private string $deliveryAddress;
	private string $number;
	private string $price;
	private string $createDate;

	public function __construct(
		int    $orderId,
		string $itemName,
		string $status,
		string $deliveryAddress,
		string $number,
		string $price,
		string $createDate
	)
	{
		$this->orderId = $orderId;
		$this->itemName = $itemName;
		$this->status = $status;
		$this->deliveryAddress = $deliveryAddress;
		$this->price = $price;
		$this->number = $number;
		$this->createDate = $createDate;
	}

	public function getOrderId(): int
	{
		return $this->orderId;
	}

	public function setOrderId(int $orderId): void
	{
		$this->orderId = $orderId;
	}


	public function getStatus(): string
	{
		return $this->status;
	}

	public function setStatus(string $status): void
	{
		$this->status = $status;
	}

	public function getNumber(): string
	{
		return $this->number;
	}

	public function setNumber(string $number): void
	{
		$this->number = $number;
	}

	public function getCreateDate(): string
	{
		return $this->createDate;
	}

	public function setCreateDate(string $createDate): void
	{
		$this->createDate = $createDate;
	}


	public function getItemName(): string
	{
		return $this->itemName;
	}

	public function setItemName(int $itemName): void
	{
		$this->itemName = $itemName;
	}

	public function getDeliveryAddress(): string
	{
		return $this->deliveryAddress;
	}

	public function setDeliveryAddress(string $deliveryAddress): void
	{
		$this->deliveryAddress = $deliveryAddress;
	}

	public function getPrice(): string
	{
		return $this->price;
	}

	public function setPrice(string $price): void
	{
		$this->price = $price;
	}

	public static function getRulesValidationOrder(): Rule
	{
		return (new Rule())
			->addRule('price', 'numeric_optional')
			->addRule('user_id', 'required')
			->addRule(['item_id', 'status_id'], 'numeric_optional')
			->addRule('address', ['required', 'min_optional:3'])
			->addRule('data_create', ['date','required'])
			->addRule('number', ['required', 'min_optional:10', 'max_optional:12']);
	}
}