<?php

namespace App\Controller;
use App\Model\User;
use Core\Database\Repo\AdminPanelRepo;
use Core\Database\Repo\CategoryListRepo;
use Core\Database\Repo\UserRepo;

class AdminController extends BaseController
{
	public function showAdminPage(?array $errors = null): void
	{
		if($this->checkAuth()) {
			$this->render('layout.php', [
				'content' => $this->strRender('AdminPage/admin.php', [
					'itemList' => AdminPanelRepo::getItemList(),
					'categoryList' => CategoryListRepo::getCategoryList(),
				]),
				'category_list' => CategoryListRepo::getObjectList(),
			]);
		}
		else
		{
			$this->render('AuthPage/auth.php', ['errors' => $errors,]);
		}
	}

	public function deleteItem(): void
	{
		$itemId = (int)$_GET['id'];
		AdminPanelRepo::deleteItem($itemId);
		$this->showAdminPage();
	}
}