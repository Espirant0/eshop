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
	public function showAdminPage($tableName, ?array $errors = null): void
	{
		$config = new Config();
		$itemsPerPage = $config->option('PRODUCT_LIMIT');
		$pageNumber = 1;
		if(isset($_GET['page']))
		{
			$pageNumber = (int)$_GET['page'];
		}
		if(empty($tableName))
		{
			$tableName = '';
		} else {
			$tableName = $tableName[0];
		}
		if (AuthService::checkAuth()) {
			$this->render('AdminPage/admin.php', [
				'objectList' => (new CategoryListRepo())->getObjectList(),
				'pageNumber' => $pageNumber,
				'tableName' => $tableName,
				'errors' => $errors,
				'page' => $pageNumber,
				'pagesCount' => $this->getPagesCount($itemsPerPage,$tableName, null),
				'title' => 'Админ-панель',
			]);
		} else {
			$this->render('AuthPage/auth.php', [
				'errors' => $errors,
				'title' => 'Авторизация',
			]);
		}
	}

	public function deleteBicycle($tableName): void
	{
		$itemId = (int)$_GET['id'];
		AdminPanelRepo::deleteBicycle($itemId);
		FileCache::deleteCacheByKey('category');
		$this->showAdminPage($tableName);
	}

	public function resetData():void
	{
		if(AuthService::checkAuth()){
			#ClearTestData::clear();
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