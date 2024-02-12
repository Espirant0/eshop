<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\Service\AuthService;
use App\Service\HttpService;
use Core\Database\Repo\AdminPanelRepo;

class EditFormController extends BaseController
{
	public function showEditFormPage(?array $errors = null): void
	{
		if(AuthService::checkAuth()) {
			$this->render('EditFormPage/edit.php', [
				'errors' => $errors,
				'itemId' => $_GET['id'],
				'table' => $_GET['table'],
				'fieldList' => AdminPanelRepo::getItemColumns($_GET['table']),
			]);
		}
		else{
			$this->render('AuthPage/auth.php', [
				'errors' => $errors,
			]);
		}
	}
	public function showAddFormPage(?array $errors = null): void
	{
		if(AuthService::checkAuth()) {
			$this->render('AddFormPages/addItem.php', [
				'errors' => $errors,
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

	public function updateValue(): void
	{
		$errors = [];
		$itemId = (int)$_GET['id'];
		$table = (string)$_GET['table'];
		$itemField = (string)$_POST['field'];
		$newValue = $_POST['value'];
		if(AdminPanelRepo::checkItemColumns($table, $itemField, $newValue)){
			if($itemField == 'title')
			{
				$files = scandir(ROOT. '/public/resources/product/img/');
				$files = array_diff($files, array('.', '..'));
				foreach ($files as $file)
				{
					if((int)explode('.',$file)[0]==$itemId)
					{
						$oldName = $file;
						break;
					}
				}
				rename(ROOT."/public/resources/product/img/$oldName",ROOT."/public/resources/product/img/$itemId.$newValue");
			}
			AdminPanelRepo::updateItem($table, $itemId, $itemField, $newValue);
			FileCache::deleteCacheByKey($table);
			$bicycleList = AdminPanelRepo::getBicycleList();
			HttpService::redirect('admin_panel');
		}
		else
		{
			$errors[] = 'Неверное значение!';
			$this->showEditFormPage($errors);
		}
	}
}