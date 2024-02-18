<?php
/**
 * @var array $errors;
 * @var string $title;
 */
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="/resources/css/reset.css">
	<link rel="stylesheet" href="/resources/css/style.css">
	<title><?=$title?></title>
</head>
<body>
<div class="auth_container">
	<div class="auth_errors">
		<?php if(!empty($errors)):?>
			<div>
				<?= implode('<br>', $errors);?>
			</div>
		<?php endif;?>
	</div>
	<div class="form_container">
		<form action="/login" method="post" class="auth_form">
			<label>
				<input type="text" name="login" class="login_input auth_input" placeholder="Введите логин" required>
			</label>
			<label>
				<input type="text" name="password" class="password_input auth_input" placeholder="Введите пароль" required>
			</label>
			<button class="auth_btn">Войти</button>
		</form>
	</div>
	<a href="/" class="home_btn">На главную</a>
</div>
</body>
</html>
