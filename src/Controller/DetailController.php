<?php

namespace App\Controller;

class DetailController extends BaseController
{
    public function showDetailPage($detail_id): void
    {
        $this->render('DetailPage/detail.php', ['detail_id' => $detail_id]);
    }
}