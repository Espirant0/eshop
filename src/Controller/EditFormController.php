<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\Model\Bicycle;
use App\Service\AuthService;
use App\Service\HttpService;
use App\Service\ImageHandler;
use Core\Database\Repo\AdminPanelRepo;
use Core\Validator\Validator;
use Core\Validator\Rules;

class EditFormController extends BaseController
{
	public function showEditFormPage($tableName, ?array $errors = null): void
	{
		$fields = AdminPanelRepo::getItemColumns($tableName[0]);
		array_shift($fields);
		$cache = new FileCache();
		$cache->set($tableName[0], $fields, 3600);
		if(AuthService::checkAuth()) {
			$this->render('EditFormPage/edit.php', [
				'errors' => $errors,
				'itemId' => $_GET['id'],
				'tableName' => $tableName[0],
				'title' => 'Редактировать',
			]);
		}
		else
        {
			$this->render('AuthPage/auth.php', [
				'errors' => $errors,
				'title' => 'Авторизация',
			]);
		}
	}
	public function showAddFormPage($tableName,?array $errors = null): void
	{
		if(AuthService::checkAuth())
        {
			$this->render('AddFormPages/addItem.php', [
				'errors' => $errors,
				'tableName' => $tableName[0],
				'title' => 'Добавить товар',
			]);
		}
		else
        {
			$this->render('AuthPage/auth.php', [
				'errors' => $errors,
				'title' => 'Авторизация',
			]);
		}
	}

	public function addItem($tableName): void
	{
		$data = $_POST;;
		$validator = new Validator();
		$rules = (new Rules())->addRule('price',['numeric','required'])
							  ->addRule('description',['min_optional:3','required'])
							  ->addRule('create_year',['required','min_optional:4'])
			                  ->addRule('title','required');


		if($validator->validate($data,$rules->getRules()))
		{
			$bicycle = new Bicycle(
				$itemId,
				$_POST['title'],
				$_POST['color_id'],
				$_POST['create_year'],
				$_POST['material_id'],
				$_POST['price'],
				$_POST['description'],
				$_POST['status'],
				$_POST['manufacturer_id'],
				$_POST['speed'],
				[$_POST['category']],
				$_POST['target_id']
			);
			AdminPanelRepo::addItem($bicycle, $images);
			HttpService::redirect('admin_panel');
		}
		else
		{
			$errors = $validator->errors();
			$this->showAddFormPage($tableName,$errors);
		}
	}

	public function updateValue($tableName): void
	{
		$data = $_POST;
		$validator = new Validator();
		$errors = [];
		$itemId = $_GET['id'];
		$table = $tableName[0];
		$fields = (new FileCache())->get($table);
		$newValues = [];

		foreach ($fields as $field)
		{
			if($_POST[$field]!=='')
			{
				$newValues[$field] = $_POST[$field];
			}
		}

		if($table === 'item')
		{
			$rules = (new Rules())
				->addRule('id','numeric_optional')
				->addRule('price',['numeric_optional','min_optional:3'])
				->addRule('description','min_optional:3')
				->addRule('create_year','min_optional:4');
		}

		if($table === 'material')
		{
			$rules = (new Rules())
				->addRule('id', 'numeric_optional')
				->addRule(['engName','name'], 'min_optional:3');
		}

		if($table === 'role')
		{
			$rules = (new Rules())
				->addRule('id', 'numeric_optional')
				->addRule(['name'], 'min_optional:3');
		}

		if($table === 'category')
		{
			$rules = (new Rules())
				->addRule('id', 'numeric_optional')
				->addRule(['engName','name'],'min_optional:3');
		}

		if($table === 'color')
		{
			$rules = (new Rules())
				->addRule('id', 'numeric_optional')
				->addRule(['engName','name'],'min_optional:3');
		}

		if($table === 'manufacturer')
		{
			$rules = (new Rules())
				->addRule('id', 'numeric_optional')
				->addRule(['name'], 'min_optional:3');
		}

		if($table === 'orders')
		{
			$rules = (new Rules())
				->addRule(['id','item_id','status_id','price','user_id'],'numeric_optional')
				->addRule('address','min_optional:3')
				->addRule('price','min_optional:4');
		}


		if($table === 'target_audience')
		{
			$rules = (new Rules())
				->addRule('id','numeric_optional')
				->addRule(['name','engName'],'min_optional:3');
		}

		if($table === 'user')
		{
			$rules = (new Rules())
				->addRule('id',['min_optional:10','numeric_optional'])
				->addRule(['name','engName'],'min_optional:3');
		}

		if(!empty($newValues))
		{
			if($validator->validate($data,$rules->getRules()))
			{
				if($table === 'item')
				{
					ImageHandler::renameImageForExistingItem($itemId, $_POST['title']);
				}

				AdminPanelRepo::updateItem($table, $itemId, $newValues);
				FileCache::deleteCacheByKey($table);
				HttpService::redirect('admin_panel');
			}
			else
			{
				$errors = $validator->errors();
				$this->showEditFormPage($tableName,$errors);
			}
		}
		else
		{
			$errors[] = 'Введите значения!';
			$this->showEditFormPage($tableName,$errors);
		}
	}
}