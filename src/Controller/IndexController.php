<?php

namespace App\Controller;
use Core\Database\Repo\AdminPanelRepo;
use Core\Database\Repo\BicycleRepo;
use Core\Database\Repo\CategoryListRepo;
class IndexController extends BaseController
{
    public function showIndexPage($category_id): void
    {
		if(!isset($_GET['category']))
		{
			$this->render('layout.php',[
				'content' => $this->strRender('MainPage/index.php', [
					'category_name' => null,
					'bicycleList' => BicycleRepo::getBicyclelist()
				]),
				'categoryList' => CategoryListRepo::getCategoryList(),
			]);
		}
		else
		{
			$this->render('layout.php',[
				'content' => $this->strRender('MainPage/index.php', [
					'category_name' => $_GET['category'],
					'bicycleList' => BicycleRepo::getBicyclelist()
				]),
				'categoryList' => CategoryListRepo::getCategoryList(),
			]);
		}

    }
}