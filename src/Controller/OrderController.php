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
<<<<<<< 0860605eb77d9a1f92f0faa650bc0811d5004397
			'content' => $this->render('OrderPage/order.php', []),
=======
			'content' => $this->strRender('OrderPage/order.php', [
				'item' => DetailRepo::getBicycleListById($itemId[0]),
			]),
>>>>>>> bd049f1180f6e8147c7b52f44ba57dbe060d5fbe
            'categoryList' => $categoryListRepo::getCategoryList()
		]);
    }

    public function showConfirmedOrderPage(): void
    {
        $categoryListRepo = new CategoryListRepo();
		$this->render('layout.php', [
			'content' => $this->render('ConfirmPage/confirmed.php', []),
            'categoryList' => $categoryListRepo::getCategoryList()
		]);
    }

	public function saveOrder($itemId){
		$price = DetailRepo::getBicycleListById($itemId[0])->getPrice();
		OrderRepo::saveOrder($itemId,$price, $_POST['number'], $_POST['address']);
		$this->showConfirmedOrderPage();
	}
}