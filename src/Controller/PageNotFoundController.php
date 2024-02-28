<?php

namespace App\Controller;

use Core\Database\Repo\CategoryListRepo;

class PageNotFoundController extends BaseController
{
	public function PageNotFoundViewer(): void
	{
		echo $this->render('layout.php', ['content' => $this->render('NotFoundPage/404.php',
			[]), 'categoryList' => CategoryListRepo::getCategoryListConsideringExistingItem(), 'title' => 'Страница не найдена',]);
	}
}