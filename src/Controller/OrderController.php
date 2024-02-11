<?php

namespace App\Controller;

use Core\Database\Repo\CategoryListRepo;

class OrderController extends BaseController
{
    // пока передаю пустой массив, потому что не знаю, что будем передавать (если будем)
    public function showOrderPage(): void
    {
        $categoryListRepo = new CategoryListRepo();

		$this->render('layout.php', [
			'content' => $this->strRender('OrderPage/order.php', []),
            'category_list' => $categoryListRepo::getCategoryList()
		]);
        #$this->render('OrderPage/order.php', []);
    }

    public function showConfirmedOrderPage(): void
    {
        $categoryListRepo = new CategoryListRepo();

		$this->render('layout.php', [
			'content' => $this->strRender('ConfirmPage/confirmed.php', []),
            'category_list' => $categoryListRepo::getCategoryList()
		]);
        #$this->render('ConfirmPage/confirmed.php', []);
    }
}