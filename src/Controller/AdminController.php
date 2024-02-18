<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\Service\AuthService;
use Core\Database\Repo\AdminPanelRepo;
use Core\Database\Repo\CategoryListRepo;
use Core\Database\Repo\OrderRepo;
use Core\Database\Repo\UserRepo;

class AdminController extends BaseController
{
	public function showAdminPage(?array $errors = null): void
	{
		if (AuthService::checkAuth()) {
			echo $this->render('AdminPage/admin.php', [
				'bicycleList' => AdminPanelRepo::getBicycleList(),
				'categoryList' => CategoryListRepo::getCategoryList(),
				'buttonList' => CategoryListRepo::getObjectList(),
				'colorList' => AdminPanelRepo::getItemList('color'),
				'manufacturerList' => AdminPanelRepo::getItemList('manufacturer'),
				'materialList' => AdminPanelRepo::getItemList('material'),
				'targetList' => AdminPanelRepo::getItemList('target_audience'),
				'userList' => UserRepo::getUserList(),
				'orderList' => OrderRepo::getOrderList(),
			]);
		} else {
			echo $this->render('AuthPage/auth.php', [
				'errors' => $errors,
			]);
		}
	}

	public function deleteBicycle(): void
	{
		$itemId = (int)$_GET['id'];
		AdminPanelRepo::deleteBicycle($itemId);
		FileCache::deleteCacheByKey('category');
		$this->showAdminPage();
	}
}