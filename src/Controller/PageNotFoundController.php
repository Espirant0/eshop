<?php

namespace App\Controller;
use Core\Database\Repo\CategoryListRepo;

class PageNotFoundController extends BaseController
{
	public function PageNotFoundViewer(): void
	{
		$this->render('layout.php', [
			'content' => $this->strRender('NotFoundPage/404.php',[]),
			'category_list' => CategoryListRepo::getCategoryList()
		]);
	}
}