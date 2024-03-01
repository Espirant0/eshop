<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\Model\Bicycle;
use App\Model\Category;
use App\Model\Order;
use App\Model\User;
use App\Service\AuthService;
use App\Service\HttpService;
use App\Service\ImageHandler;
use App\Service\Validator;
use Core\Database\Repo\AdminPanelRepo;

class EditFormController extends BaseController
{
	public function showEditFormPage($tableName, ?array $errors = null): void
	{
		$itemId = (int)$_GET['id'];
		if ($itemId === 0)
		{
			throw new \Exception('ID not int', -1);
		}
		$fields = AdminPanelRepo::getItemColumns($tableName[0]);
		array_shift($fields);
		$cache = new FileCache();
		$cache->set($tableName[0], $fields, 3600);
		if (AuthService::checkAuth())
		{
			echo $this->render('EditFormPage/edit.php', [
				'errors' => $errors,
				'itemId' => $itemId,
				'tableName' => $tableName[0],
				'title' => 'Редактировать',
			]);
		} else
		{
			echo $this->render('AuthPage/auth.php', [
				'errors' => $errors,
				'title' => 'Авторизация',
			]);
		}
	}

	public function showAddFormPage($tableName, ?array $errors = null): void
	{
		if (AuthService::checkAuth())
		{
			echo $this->render('AddFormPages/addItem.php', [
				'errors' => $errors,
				'tableName' => $tableName[0],
				'title' => 'Добавить товар',
			]);
		} else
		{
			echo $this->render('AuthPage/auth.php', [
				'errors' => $errors,
				'title' => 'Авторизация',
			]);
		}
	}

	public function addItem($tableName): void
	{
		$images = [];

		if (!empty($_FILES['files']['name'][0]))
		{
			$check = ImageHandler::canUpload($_FILES['files']);
			if ($check === true)
			{
				$images = $_FILES['files'];
			} else
			{
				$errors[] = [$check];
				$this->showAddFormPage($tableName, $errors);
				return;
			}
		}

		$itemId = AdminPanelRepo::getLastFreeId();

		foreach ($_POST as $key => $value)
		{
			$_POST[$key] = htmlspecialchars($value, ENT_QUOTES);
		}
		$data = $_POST;

		$validator = new Validator();
		$rules = Bicycle::getRulesValidationItem();

		if ($validator->validate($data, $rules->getRules()))
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
		} else
		{
			$errors = $validator->errors();
			$this->showAddFormPage($tableName, $errors);
		}
	}

	public function updateValue($tableName): void
	{
		foreach ($_POST as $key => $value)
		{
			$_POST[$key] = htmlspecialchars($value, ENT_QUOTES);
		}
		$data = $_POST;
		$validator = new Validator();
		$errors = [];
		$itemId = $_GET['id'];
		$table = $tableName[0];
		$fields = (new FileCache())->get($table);
		$newValues = [];

		foreach ($fields as $field)
		{
			if ($_POST[$field] !== '')
			{
				$newValues[$field] = $_POST[$field];
			}
		}

		switch ($table)
		{
			case 'item':
				$rules = Bicycle::getRulesValidationItem();
				break;

			case 'orders':
				$rules = Order::getRulesValidationOrder();
				break;

			case 'user':
				$rules = User::getRulesValidationUser();
				break;

			case 'manufacturer':
			case 'role':
			case 'category':
			case 'material':
			case 'color':
			case 'target_audience':
				$rules = Category::getRulesValidationCategory();
		}

		if (!empty($newValues))
		{
			if ($validator->validate($data, $rules->getRules()))
			{
				if ($table === 'item')
				{
					ImageHandler::renameImageForExistingItem($itemId, $_POST['title']);
				}

				AdminPanelRepo::updateItem($table, $itemId, $newValues);
				FileCache::deleteCacheByKey($table);
				HttpService::redirect('admin_panel');
			} else
			{
				$errors = $validator->errors();
				$this->showEditFormPage($tableName, $errors);
			}
		} else
		{
			$errors[] = ['Введите значения!'];
			$this->showEditFormPage($tableName, $errors);
		}
	}
}