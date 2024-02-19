<?php

namespace App\Controller;

use App\Service\HttpService;
use Core\Database\Repo\UserRepo;

class AuthController extends BaseController
{
	public function showAuthPage(?array $errors = null): void
	{
		$this->render('AuthPage/auth.php', [
			'errors' => $errors,
			'title' => 'Авторизация',
			]);
	}

	public function userLogin(): void
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
			$login = $_POST['login'];
			$password = $_POST['password'];

			$error = 'Неверный логин или пароль';
			$user = UserRepo::getUserByLogin($login);

			if (!$user || $user->getRole() !== 'Администратор')
            {
				$errors[] = $error;
				$this->showAuthPage($errors);
			}
            else
            {
				$isPasswordCorrect = password_verify($password, $user->getPassword());

				if (!$isPasswordCorrect)
                {
					$errors[] = $error;
					$this->showAuthPage($errors);
				}

				if (empty($errors))
                {
					session_start();
					$_SESSION['USER'] = $user;
					HttpService::redirect('admin_panel');
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