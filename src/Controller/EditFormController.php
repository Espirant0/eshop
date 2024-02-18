<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\Service\AuthService;
use App\Service\HttpService;
use App\Service\ImageHandler;
use Core\Database\Repo\AdminPanelRepo;
use Core\Validator\Validator;

class EditFormController extends BaseController
{
	public function showEditFormPage(?array $errors = null): void
	{
		if (AuthService::checkAuth())
        {
			echo $this->render('EditFormPage/edit.php', [
				'errors' => $errors,
				'itemId' => $_GET['id'],
				'table' => $_GET['table'],
				'fieldList' => AdminPanelRepo::getItemColumns($_GET['table']),
			]);
		}
		else
        {
			echo $this->render('AuthPage/auth.php', [
				'errors' => $errors,
			]);
		}
	}
	public function showAddFormPage(?array $errors = null): void
	{
		if(AuthService::checkAuth())
        {
			echo $this->render('AddFormPages/addItem.php', [
				'errors' => $errors,
			]);
		}
		else
        {
			echo $this->render('AuthPage/auth.php', [
				'errors' => $errors,
			]);
		}
	}

	public function addItem(): void
	{
		$data = ['title'=>$_POST['title'],
				 'category'=>$_POST['category'],
				 'create_year'=>$_POST['create_year'],
				 'price'=>$_POST['price'],
				 'description'=>$_POST['description'],
				 'status'=>$_POST['status'],
				 'manufacturer_id'=>$_POST['manufacturer_id'],
				 'material_id'=>$_POST['material_id'],
				 'color_id'=>$_POST['color_id']];

		$rules = ['title' => ['required'], //если нужно необязательным просто required убрать,я тестил просто
				  'category'=>['required','numeric'],
				  'create_year'=>['required','numeric','min:4'],
				  'price'=>['required','numeric'],
				  'description'=>['required','min:3'],
				  'status'=>['required','numeric'],
				  'manufacturer_id'=>['required','numeric'],
				  'material_id'=>['required','numeric'],
				  'color_id'=>['required','numeric']];

		$validator = new Validator();

		if($validator->validate($data,$rules) == true)
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
		else
		{
			$errors = $validator->errors();
			$this->showAddFormPage($errors);
		}
	}

	public function updateValue(): void
	{
		$errors = [];
		$itemId = (int)$_GET['id'];
		$table = (string)$_GET['table'];
		$itemField = (string)$_POST['field'];
		$newValue = $_POST['value'];
		if(AdminPanelRepo::checkItemColumns($table, $itemField, $newValue))
        {
			if ($itemField == 'title')
			{
				ImageHandler::renameImageForExistingItem($itemId, $newValue);
			}

			AdminPanelRepo::updateItem($table, $itemId, $itemField, $newValue);
			FileCache::deleteCacheByKey($table);
			HttpService::redirect('admin_panel');
		}
		else
		{
			$errors[] = 'Неверное значение!';
			$this->showEditFormPage($errors);
		}
	}
}