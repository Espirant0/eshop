<?php

namespace App\Controller;

use App\Model\Category;
use App\Model\CategoryList;
use Core\Database\Repo\CategoryListRepo;
use Core\Database\Repo\DetailRepo;

class DetailController extends BaseController
{
    public function showDetailPage($itemId): void
    {

        $categoryListRepo = new CategoryListRepo();

		$this->render('layout.php', [
			'content' => $this->strRender('DetailPage/detail.php', ['item' => DetailRepo::getBicycleListById($itemId[0])]),
            'categoryList' => $categoryListRepo::getCategoryList()
		]);
    }
}