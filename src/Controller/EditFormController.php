<?php

namespace App\Controller;

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
	public function addItem():void
	{
		$title = $_POST['title'];
		$colorId = $_POST['color_id'];
		$createYear = $_POST['create_year'];
		$materialId = $_POST['material_id'];
		$description = $_POST['description'];
		$price = $_POST['price'];
		$status = $_POST['status'];
		$manufacturerId = $_POST['manufacturer_id'];
		AdminPanelRepo::addItem($title, $createYear, $price, $description, $status, $manufacturerId, $materialId,$colorId);
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
			AdminPanelRepo::updateItem($table, $itemId, $itemField, $newValue);
			HttpService::redirect('admin_panel');
		}
		else
		{
			$errors[] = 'Неверное значение!';
			$this->showEditFormPage($errors);
		}
	}
}