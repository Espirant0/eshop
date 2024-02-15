<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\Config\Config;
use App\Service\AuthService;
use Core\Database\Repo\AdminPanelRepo;
use Core\Database\Repo\CategoryListRepo;
use Core\Database\Repo\OrderRepo;
use Core\Database\Repo\UserRepo;

class AdminController extends BaseController
{
	public function showAdminPage(?array $errors = null): void
	{
		$config = new Config();
		$pageLimit = (int)$config->option('PRODUCT_LIMIT');
		$pagesCount = $this->getPagesCount($pageLimit, 'item');
		$pageNumber = 1;
		if(isset($_GET['page']))
		{
			$pageNumber = $_GET['page'];
		}
		if (AuthService::checkAuth()) {
			$this->render('AdminPage/admin.php', [
				'bicycleList' => AdminPanelRepo::getBicycleList($pageNumber),
				'categoryList' => CategoryListRepo::getCategoryList(),
				'buttonList' => CategoryListRepo::getObjectList(),
				'colorList' => AdminPanelRepo::getItemList('color'),
				'manufacturerList' => AdminPanelRepo::getItemList('manufacturer'),
				'materialList' => AdminPanelRepo::getItemList('material'),
				'targetList' => AdminPanelRepo::getItemList('target_audience'),
				'userList' => UserRepo::getUserList(),
				'orderList' => OrderRepo::getOrderList(),
				'pagesCount' => $pagesCount,
			]);
		} else {
			$this->render('AuthPage/auth.php', [
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