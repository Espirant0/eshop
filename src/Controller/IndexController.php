<?php

namespace App\Controller;
use Core\Database\Repo\AdminPanelRepo;
use Core\Database\Repo\BicycleRepo;
use Core\Database\Repo\CategoryListRepo;

class IndexController extends BaseController
{
    public function showIndexPage($categoryName): void
    {
        if (empty($categoryName))
        {
            $categoryName = '';
        }
        else
        {
            $categoryName = $categoryName[0];
        }

        $this->render('layout.php',[
            'content' => $this->strRender('MainPage/index.php', [
                'category_name' => $categoryName,
                'bicycleList' => BicycleRepo::getBicyclelist($categoryName)
            ]),
            'categoryList' => CategoryListRepo::getCategoryList(),
        ]);
    }
}