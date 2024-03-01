<?php
/**
 * @var array $errors ;
 * @var string $title ;
 */
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="icon" href="/resources/img/icon.png" type="image/x-icon">
	<link rel="stylesheet" href="/resources/css/reset.css">
	<link rel="stylesheet" href="/resources/css/style.css">
	<title><?= $title ?></title>
</head>
<body>
<div class="auth_container">
	<div class="auth_img_inner">
		<img src="resources/img/user-circle-solid.svg" class="auth_img">
	</div>
	<div class="auth_errors">
		<?php if (!empty($errors)): ?>
			<?php foreach ($errors as $error): ?>
				<div>
					<?php foreach ($error as $errorName): ?>
						<?= $errorName; ?>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<div class="form_container">
		<form action="/login" method="post" class="auth_form">
			<label>
				<input type="text" name="login" class="login_input auth_input" placeholder="Логин" required>
			</label>
			<label>
				<input type="password" name="password" class="password_input auth_input" placeholder="Пароль" required>
			</label>
			<button class="auth_btn">Войти</button>
		</form>
	</div>
	<a href="/" class="home_btn">На главную</a>
</div>
</body>
</html>
