<?php

namespace App\Controller;
<<<<<<< 206341a4fb6ab5cbdcd0026864b54ef6eea92fba
=======
use App\Cache\FileCache;
use Core\Database\Repo\AdminPanelRepo;
>>>>>>> 39d6355c989bdad7fdd1d587c43dd5be04643151
use Core\Database\Repo\CategoryListRepo;

class IndexController extends BaseController
{
    public function showIndexPage($category_id): void
    {
        $categoryListRepo = new CategoryListRepo();

		$this->render('layout.php', [
			'content' => $this->strRender('MainPage/index.php', ['category_id' => $category_id]),
            'category_list' => $categoryListRepo::getCategoryList(),
		]);


    }
}