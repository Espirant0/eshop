<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\Config\Config;
use App\Service\AuthService;
use App\Service\HttpService;
use App\Service\ClearTestData;
use Core\Database\Repo\AdminPanelRepo;
use Core\Database\Repo\CategoryListRepo;

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
				'objectList' => (new CategoryListRepo())->getObjectList(),
				'errors' => $errors,
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

	public function resetData():void
	{
		if(AuthService::checkAuth()){
			ClearTestData::clear();
			FileCache::deleteAllCache(); #Костыль, не очищается нижний кэш при полном удалении
			FileCache::deleteCacheByKey('categoriesWithoutEmptyCategory');
			HttpService::redirect('admin_panel');
		}
		else
		{
			HttpService::redirect('auth');
		}
	}
}