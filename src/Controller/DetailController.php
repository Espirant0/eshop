<?php

namespace App\Controller;

use App\Model\Category;
use App\Model\CategoryList;
use Core\Database\Repo\CategoryListRepo;

class DetailController extends BaseController
{
    public function showDetailPage($detail_id): void
    {
        $categoryListRepo = new CategoryListRepo();

		$this->render('layout.php', [
			'content' => $this->strRender('DetailPage/detail.php', ['detail_id' => 0]),
            'category_list' => $categoryListRepo::getCategoryList()
		]);
    }
}