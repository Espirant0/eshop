<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\Service\AuthService;
use App\Service\HttpService;
use App\Service\ImageHandler;
use Core\Database\Repo\AdminPanelRepo;

class EditFormController extends BaseController
{
	public function showEditFormPage($tableName, ?array $errors = null): void
	{
		$fields = AdminPanelRepo::getItemColumns($tableName[0]);
		$cache = new FileCache();
		$cache->set($tableName[0], $fields, 3600);
		if(AuthService::checkAuth()) {
			$this->render('EditFormPage/edit.php', [
				'errors' => $errors,
				'itemId' => $_GET['id'],
				'tableName' => $tableName[0],
			]);
		}
		else{
			$this->render('AuthPage/auth.php', [
				'errors' => $errors,
			]);
		}
	}
	public function showAddFormPage($tableName,?array $errors = null): void
	{
		if(AuthService::checkAuth()) {
			$this->render('AddFormPages/addItem.php', [
				'errors' => $errors,
				'tableName' => $tableName[0],
			]);
		}
		else{
			$this->render('AuthPage/auth.php', [
				'errors' => $errors,
			]);
		}
	}

	public function addItem(): void
	{
		AdminPanelRepo::addItem(
			$_POST['title'],
			$_POST['category'],
			$_POST['create_year'],
			$_POST['price'],
			$_POST['description'],
			$_POST['status'],
			$_POST['manufacturer_id'],
			$_POST['material_id'],
			$_POST['color_id']
		);
		HttpService::redirect('admin_panel');
	}

	public function updateValue($tableName): void
	{
		$errors = [];
		$itemId = $_GET['id'];
		$table = $tableName[0];
		$fields = (new FileCache())->get($table);
		$newValues = [];
		foreach ($fields as $field)
		{
			if($_POST[$field]!=='') {
				$newValues[$field] = $_POST[$field];
			}
		}
		if($table === 'item') {
			ImageHandler::renameImageForExistingItem($itemId, $_POST['title']);
		}
		if(!empty($newValues)) {
			AdminPanelRepo::updateItem($table, $itemId, $newValues);
			FileCache::deleteCacheByKey($table);
			HttpService::redirect('admin_panel');
		}
		else
		{
			$errors[] = 'Вы не ввели значение!';
			$this->showEditFormPage($errors);
		}

	}
}