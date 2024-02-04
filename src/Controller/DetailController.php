<?php

namespace App\Controller;

use Core\Database\Repo\CategoryListRepo;

class DetailController extends BaseController
{
    public function showDetailPage($detail_id): void
    {
		$this->render('layout.php',[
			'content' => $this->strRender('DetailPage/detail.php', ['detail_id' => 0]),
			'category_list' => CategoryListRepo::getCategoryList()
		]);
    }
}