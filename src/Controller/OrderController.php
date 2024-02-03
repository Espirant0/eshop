<?php

namespace App\Controller;

class OrderController extends BaseController
{
    // пока передаю пустой массив, потому что не знаю, что будем передавать (если будем)
    public function showOrderPage(): void
    {
        $this->render('OrderPage/order.php', []);
    }

    public function showConfirmedOrderPage(): void
    {
        $this->render('ConfirmPage/confirmed.php', []);
    }
}