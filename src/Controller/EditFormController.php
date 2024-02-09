<?php

namespace App\Controller;

use App\Service\DBHandler;
use Core\Database\Repo\AdminPanelRepo;
use Core\Database\Repo\CategoryListRepo;

class EditFormController extends BaseController
{
	public function showEditFormPage(?array $errors = null): void
	{
		if($this->checkAuth()) {
			$this->render('EditFormPage/edit.php', [
				'errors' => $errors,
				'itemId' => $_GET['id'],
			]);
		}
		else{
			$this->render('AuthPage/auth.php', ['errors' => $errors,]);
		}
	}
	public function showAddFormPage(?array $errors = null): void
	{
		if($this->checkAuth()) {
			$this->render('AddFormPages/addItem.php', [
				'errors' => $errors,
			]);
		}
		else{
			$this->render('AuthPage/auth.php', ['errors' => $errors,]);
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
		header('Location: /admin_panel');
	}
	public function updateValue(): void
	{
		$errors = [];
		$itemId = (int)$_GET['id'];
		$itemField = (string)$_POST['field'];
		$newValue = $_POST['value'];
		if(AdminPanelRepo::checkItemColumns($itemField, $newValue)){
			AdminPanelRepo::updateItem($itemId, $itemField, $newValue);
			header('Location: /admin_panel');
		}
		else
		{
			$errors[] = 'Неверное поле!';
			$this->showEditFormPage($errors);
		}
	}
}