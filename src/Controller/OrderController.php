<?php

namespace App\Controller;

use Core\Database\Repo\CategoryListRepo;
use Core\Database\Repo\DetailRepo;
use Core\Database\Repo\OrderRepo;

class OrderController extends BaseController
{
    public function showOrderPage($itemId): void
    {
        $categoryListRepo = new CategoryListRepo();

		$this->render('layout.php', [
			'content' => $this->strRender('OrderPage/order.php', [
				'item' => DetailRepo::getBicycleListById($itemId[0]),
			]),
            'categoryList' => $categoryListRepo::getCategoryList()
		]);
    }

    public function showConfirmedOrderPage(): void
    {
        $categoryListRepo = new CategoryListRepo();
		$this->render('layout.php', [
			'content' => $this->strRender('ConfirmPage/confirmed.php', []),
            'categoryList' => $categoryListRepo::getCategoryList()
		]);
    }

	public function saveOrder($itemId){
		$price = DetailRepo::getBicycleListById($itemId[0])->getPrice();
		OrderRepo::saveOrder($itemId,$price, $_POST['number'], $_POST['address']);
		$this->showConfirmedOrderPage();
	}
}