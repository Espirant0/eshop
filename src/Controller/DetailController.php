<?php

namespace App\Controller;

use Core\Database\Repo\CategoryListRepo;
use Core\Database\Repo\DetailRepo;

class DetailController extends BaseController
{
    public function showDetailPage($itemId): void
    {
        $categoryListRepo = new CategoryListRepo();

		echo $this->render('layout.php', [
			'content' => $this->render('DetailPage/detail.php', [
				'bicycle' => DetailRepo::getBicycleById($itemId[0])
			]),
            'categoryList' => $categoryListRepo::getCategoryListConsideringExistingItem()
		]);
    }
}