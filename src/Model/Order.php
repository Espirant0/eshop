<?php

namespace App\Model;

class Order
{
	private string $ID;
	private Bicycle $order;
	private string $status;
	private string $deliveryAddress;
	private User $user;
	private string $createDate;

	public function __construct(
        string $ID,
        Bicycle $order,
        string $status,
        string $deliveryAddress,
        User $user,
        string $createDate
    )
	{
		$this->ID = $ID;
		$this->order = $order;
		$this->status = $status;
		$this->deliveryAddress = $deliveryAddress;
		$this->user = $user;
		$this->createDate = $createDate;
	}

	public function getID():string
	{
		return $this->ID;
	}

	public function setID(string $ID):void
	{
		$this->ID = $ID;
	}

	public function getOrder():Bicycle
	{
		return $this->order;
	}

	public function setOrder(Bicycle $order):void
	{
		$this->order = $order;
	}

	public function getStatus():string
	{
		return $this->status;
	}

	public function setStatus(string $status):void
	{
		$this->status = $status;
	}

	public function getAddress():string
	{
		return $this->deliveryAddress;
	}

	public function setAddress(string $deliveryAddress):void
	{
		$this->deliveryAddress = $deliveryAddress;
	}

	public function getUser():User
	{
		return $this->user;
	}

	public function setUser(User $user):void
	{
		$this->user = $user;
	}

	public function getCreateDate():string
	{
		return $this->createDate;
	}

	public function setCreateDate(string $createDate):void
	{
		$this->createDate = $createDate;
	}

	public function getAllOrderData():array
	{
		return [$this->ID, $this->order, $this->status, $this->deliveryAddress, $this->user, $this->createDate];
	}
}