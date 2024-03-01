<?php

namespace App\Controller;

use App\Model\Rule;
use App\Service\HttpService;
use Core\Database\Repo\UserRepo;
use App\Service\Validator;

class AuthController extends BaseController
{
	public function showAuthPage(?array $errors = null): void
	{
		echo $this->render('AuthPage/auth.php', [
			'errors' => $errors,
			'title' => 'Авторизация',
		]);
	}

	public function userLogin(): void
	{
		$validator = new Validator();
		$rule = (new Rule())->addRule(['login', 'password'], 'required');
		$rules = $rule->getRules();
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$error = ['Неверный логин или пароль'];
			foreach ($_POST as $key => $value)
			{
				$_POST[$key] = htmlspecialchars($value, ENT_QUOTES);
			}
			$data = $_POST;
			if (!$validator->validate($data, $rules))
			{
				$errors = $validator->errors();
				$this->showAuthPage($errors);
			} else
			{
				$login = $_POST['login'];
				if ($login[0] === '8')
				{
					$login = str_replace($login[0], '7', $login);
				}
				if ($login[0] === '+')
				{
					$login = substr($login, 1);
				}
				$password = $_POST['password'];

				$user = UserRepo::getUserByLogin($login);

				if (!$user || $user->getRole() !== 'Администратор')
				{
					$errors[] = $error;
					$this->showAuthPage($errors);
				} else
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