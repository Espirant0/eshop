<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\Service\HttpService;
use Core\Database\Repo\CategoryListRepo;
use Core\Database\Repo\DetailRepo;
use Core\Database\Repo\OrderRepo;

class OrderController extends BaseController
{
    public function showOrderPage(): void
    {
        $categoryListRepo = new CategoryListRepo();

		echo $this->render('layout.php', [
			'content' => $this->render('OrderPage/order.php', [
				'bicycle' => (new FileCache())->get('bicycle'),
			]),
            'categoryList' => $categoryListRepo::getCategoryListConsideringExistingItem()
		]);
    }

    public function showConfirmedOrderPage(): void
    {
        $categoryListRepo = new CategoryListRepo();
		echo $this->render('layout.php', [
			'content' => $this->render('ConfirmPage/confirmed.php', []),
            'categoryList' => $categoryListRepo::getCategoryListConsideringExistingItem()
		]);
    }

	public function saveOrder(){
		$item = (new FileCache())->get('bicycle');
		$itemId = $item->getId();
		$price = $item->getPrice();
		OrderRepo::saveOrder($itemId, $price, $_POST['number'], $_POST['address']);
		HttpService::redirect('confirmed');
	}
}