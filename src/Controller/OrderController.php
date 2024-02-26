<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\Service\HttpService;
use Core\Database\Repo\CategoryListRepo;
use Core\Database\Repo\OrderRepo;

class OrderController extends BaseController
{
	public function showOrderPage(): void
	{
		$categoryListRepo = new CategoryListRepo();
		$bicycle = (new FileCache())->get('bicycle');
		$this->render('layout.php', [
			'content' => $this->strRender('OrderPage/order.php', [
				'bicycle' => $bicycle,
			]),
			'categoryList' => $categoryListRepo::getCategoryListConsideringExistingItem(),
			'title' => $bicycle->getName(),
		]);
	}

	public function showConfirmedOrderPage(): void
	{
		$categoryListRepo = new CategoryListRepo();
		$this->render('layout.php', [
			'content' => $this->strRender('ConfirmPage/confirmed.php', []),
			'categoryList' => $categoryListRepo::getCategoryListConsideringExistingItem(),
			'title' => 'Заказ',
		]);
	}

	public function saveOrder()
	{
		$item = (new FileCache())->get('bicycle');
		$itemId = $item->getId();
		$price = $item->getPrice();
		OrderRepo::saveOrder($itemId, $price, $_POST['number'], $_POST['address']);
		HttpService::redirect('confirmed');
	}
}