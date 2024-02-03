<?php

namespace App\Controller;

class IndexController extends BaseController
{
    public function showIndexPage($category_id): void
    {
        $this->render('MainPage/index.php', ['category_id' => $category_id]);
    }
}