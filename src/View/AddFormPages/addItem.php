<?php
/**
 * @var string $tableName;
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
		<?php if (!empty($errors)): ?>
			<div>
				<?php foreach ($errors as $field => $messages): ?>
					<div>
						<ul>
							<?php foreach ($messages as $message): ?>
								<li><?= $message ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="form_container">
		<form action="/admin_panel/<?=$tableName?>/add" method="post" class="auth_form">
			<label>
				<input type="text" name="title" class="login_input auth_input" placeholder="Введите название" required>
			</label>
			<label>
				<input type="number" name="category" class="login_input auth_input" placeholder="Введите id категории" required>
			</label>
			<label>
				<input type="number" name="color_id" class="password_input auth_input" placeholder="Введите id цвета" required>
			</label>
			<label>
				<input type="number" name="create_year" class="login_input auth_input" placeholder="Введите год выпуска" required>
			</label>
			<label>
				<input type="number" name="material_id" class="password_input auth_input" placeholder="Введите id материала" required>
			</label>
			<label>
				<input type="number" name="price" class="login_input auth_input" placeholder="Введите цену" required>
			</label>
			<label>
				<input type="text" name="description" class="password_input auth_input" placeholder="Введите описание" required>
			</label>
			<label>
				<input type="number" name="status" class="password_input auth_input" placeholder="Введите id статуса" required>
			</label>
			<label>
				<input type="number" name="manufacturer_id" class="password_input auth_input" placeholder="Введите id производителя" required>
			</label>
			<button class="auth_btn">Добавить</button>
		</form>
	</div>
	<a href="/admin_panel" class="home_btn">Назад</a>
</div>
</body>
</html>

