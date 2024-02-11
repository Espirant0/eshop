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

        $bicycleList = BicycleRepo::getBicyclelist($categoryName);

        $this->render('layout.php',[
            'content' => $this->render('MainPage/index.php', [
                'category_name' => $categoryName,
                'bicycleList' => $bicycleList
            ]),
            'categoryList' => CategoryListRepo::getCategoryList(),
        ]);


    }
}