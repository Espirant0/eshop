<?php

namespace App\Controller;
use App\Cache\FileCache;
use Core\Database\Repo\CategoryListRepo;

class IndexController extends BaseController
{
    public function showIndexPage($category_id): void
    {
        $categoryListRepo = new CategoryListRepo();

		$this->render('layout.php', [
			'content' => $this->strRender('MainPage/index.php', ['category_id' => $category_id]),
            'categoryList' => $categoryListRepo::getCategoryList(),
		]);


    }
}