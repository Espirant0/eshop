<?php

namespace App\Controller;

use Core\Database\Repo\UserRepo;
use App\Controller\AdminController;

class AuthController extends BaseController
{
	public function showAuthPage(?array $errors = null): void
	{
		$this->render('AuthPage/auth.php', ['errors' => $errors,]);
	}

	public function userLogin(): void
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$login = $_POST['login'];
			$password = $_POST['password'];

			$error = 'Неверный логин или пароль';
			$user = UserRepo::getUserByLogin($login);

			if (!$user)
            {
				$errors[] = $error;
				$this->showAuthPage($errors);
			}
            else
            {
				//$isPasswordCorrect = password_verify($password, $user->getPassword());
				$isPasswordCorrect = !strnatcmp($password, $user->getPassword());

				if (!$isPasswordCorrect)
                {
					$errors[] = $error;
					$this->showAuthPage($errors);
				}

				if (empty($errors))
                {
					session_start();
					$_SESSION['USER'] = $user;
					$admin = new \App\Controller\AdminController();
					$admin->showAdminPage();
				}
			}
		}
	}

	public function signOut(): void
	{
		session_start();
		unset($_SESSION['USER']);
		session_unset();
		session_destroy();

		$this->showAuthPage();
	}
}