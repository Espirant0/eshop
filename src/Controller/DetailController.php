<?php

namespace App\Controller;

use Core\Database\Repo\CategoryListRepo;
use Core\Database\Repo\DetailRepo;

class DetailController extends BaseController
{
    public function showDetailPage($itemId): void
    {
        $categoryListRepo = new CategoryListRepo();

		$this->render('layout.php', [
			'content' => $this->strRender('DetailPage/detail.php', [
				'bicycle' => DetailRepo::getBicycleById($itemId[0])
			]),
            'categoryList' => $categoryListRepo::getCategoryList()
		]);
    }
}