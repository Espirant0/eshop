<?php

namespace App\Controller;

use App\Service\DBHandler;
use Core\Database\Repo\AdminPanelRepo;
use Core\Database\Repo\CategoryListRepo;

class EditFormController extends BaseController
{
	public function showEditFormPage(?array $errors = null): void
	{
		if ($this->checkAuth())
        {
			$this->render('EditFormPage/edit.php', [
				'errors' => $errors,
				'itemId' => $_GET['id'],
				'fieldList' => AdminPanelRepo::getItemColumns(),
			]);
		}
		else
        {
			$this->render('AuthPage/auth.php', ['errors' => $errors,]);
		}
	}
	public function showAddFormPage(?array $errors = null): void
	{
		if($this->checkAuth())
        {
			$this->render('AddFormPages/addItem.php', [
				'errors' => $errors,
			]);
		}
		else
        {
			$this->render('AuthPage/auth.php', ['errors' => $errors,]);
		}
	}

	public function addItem(): void
	{
		AdminPanelRepo::addItem(
            $_POST['title'],
            $_POST['create_year'],
            $_POST['price'],
            $_POST['description'],
            $_POST['status'],
            $_POST['manufacturer_id'],
            $_POST['material_id'],
            $_POST['color_id']
        );

		header('Location: /admin_panel');
	}

	public function updateValue(): void
	{
		$errors = [];
		$itemId = (int)$_GET['id'];
		$itemField = (string)$_POST['field'];
		$newValue = $_POST['value'];

		if (AdminPanelRepo::checkItemColumns($itemField, $newValue))
        {
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