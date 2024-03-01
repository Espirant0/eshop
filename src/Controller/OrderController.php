<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\Service\HttpService;
use Core\Database\Repo\CategoryListRepo;
use Core\Database\Repo\OrderRepo;
use App\Service\Validator;
use App\Model\Order;

class OrderController extends BaseController
{
	public function showOrderPage(?array $errors = null): void
	{
		$categoryListRepo = new CategoryListRepo();
		$bicycle = (new FileCache())->get('bicycle');
		echo $this->render('layout.php', [
			'content' => $this->render('OrderPage/order.php', [
				'bicycle' => $bicycle,
				'errors' => $errors
			]),
			'categoryList' => $categoryListRepo::getCategoryListConsideringExistingItem(),
			'title' => $bicycle->getName(),
		]);
	}

	public function showConfirmedOrderPage(): void
	{
		$categoryListRepo = new CategoryListRepo();
		echo $this->render('layout.php', [
			'content' => $this->render('ConfirmPage/confirmed.php', []),
			'categoryList' => $categoryListRepo::getCategoryListConsideringExistingItem(),
			'title' => 'Заказ',
		]);
	}

	public function saveOrder()
	{
		$item = (new FileCache())->get('bicycle');
		$itemId = $item->getId();
		$price = $item->getPrice();
		$data = $_POST;
		$validator = new Validator();
		$rules = Order::getRulesValidationOrder();
		if ($validator->validate($data, $rules->getRules()))
		{
			OrderRepo::saveOrder($itemId, $price, $_POST['number'], $_POST['address']);
			HttpService::redirect('confirmed');
		} else
		{
			$errors = $validator->errors();
			$this->showOrderPage($errors);
		}
	}
}