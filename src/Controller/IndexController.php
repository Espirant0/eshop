<?php

namespace App\Controller;
use Core\Database\Repo\AdminPanelRepo;
use Core\Database\Repo\CategoryListRepo;

class IndexController extends BaseController
{
    public function showIndexPage($category_id): void
    {
		$this->render('layout.php', [
			'content' => $this->strRender('MainPage/index.php', ['category_id' => $category_id]),
			'category_list' => CategoryListRepo::getCategoryList(),
		]);
    }
}