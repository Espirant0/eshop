<?php
/**
 * @var int $itemId;
 * @var array $fieldList;
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
	<title>edit</title>
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
		<form action="/admin_panel/update?id=<?=$itemId;?>" method="post" class="auth_form">
			<label>
				<select name="field" class="login_input auth_input" required>
					<?php foreach ($fieldList as $field):?>
						<option><?=$field?></option>
					<?php endforeach;?>
				</select>
			</label>
			<label>
				<input type="text" name="value" class="password_input auth_input" placeholder="Введите новое значение" required>
			</label>
			<button class="auth_btn">Обновить</button>
		</form>
	</div>
	<a href="/admin_panel" class="home_btn">Назад</a>
</div>
</body>
</html>

