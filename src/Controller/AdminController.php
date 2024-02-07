<?php

namespace App\Controller;
use App\Model\User;
use Core\Database\Repo\AdminPanelRepo;
use Core\Database\Repo\CategoryListRepo;
use Core\Database\Repo\UserRepo;

class AdminController extends BaseController
{
	public function showAdminPage(): void
	{
		$this->render('layout.php',[
			'content' => $this->strRender('AdminPage/admin.php', ['itemList' => AdminPanelRepo::getItemList(),]),
			'category_list' => CategoryListRepo::getCategoryList(),
		]);
	}

	public function checkAuth():void
	{
		session_start();
		if(!isset($_SESSION['USER'])){
			$this->render('AuthPage/auth.php',[]);
		}
		else{
			$this->showAdminPage();
		}
	}
}