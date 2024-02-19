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
				<input type="number" name="price" class="login_input auth_input" placeholder="Введите цену" required>
			</label>
			<label>
				<input type="text" name="description" class="password_input auth_input" placeholder="Введите описание" required>
			</label>
			<label>
				<input type="number" name="create_year" class="login_input auth_input" placeholder="Введите год выпуска" required>
			</label>
			<label for="category">Выберите категорию:</label>
			<select name="category" >
				<option value="1">Электрический</option>
				<option value="2">BMX</option>
				<option value="3">Горный</option>
				<option value="4">Дорожный</option>
				<option value="5">Фэтбайк</option>
				<option value="6">Подростковый</option>
				<option value="7">Детский</option>
			</select>
			<label for="color_id">Выберите цвет:</label>
			<select name="color_id" >
				<option value="1">Чёрный</option>
				<option value="2">Красный</option>
				<option value="3">Жёлтый</option>
				<option value="4">Серый</option>
				<option value="5">Оранжевый</option>
				<option value="6">Хаки</option>
				<option value="7">Синий</option>
				<option value="8">Розовый</option>
				<option value="9">Фиолетовый</option>
			</select>
			<label for="material_id">Выберите материал:</label>
			<select name="material_id" >
				<option value="1">Сталь</option>
				<option value="2">Алюминий</option>
			</select>
			<label for="status">Выберите статус:</label>
			<select name="status" >
				<option value="1">Доступен</option>
				<option value="0">Сокрыт</option>
			</select>
			<label for="manufacturer_id">Выберите производителя:</label>
			<select name="manufacturer_id" >
				<option value="1">Ortler</option>
				<option value="2">Specialized</option>
				<option value="3">Giant</option>
				<option value="4">Bulls</option>
				<option value="5">TechTeam</option>
				<option value="6">Fracren</option>
				<option value="7">Velopro</option>
				<option value="8">Trinx</option>
				<option value="9">Author</option>
			</select>
			<button class="auth_btn">Добавить</button>
		</form>
	</div>
	<a href="/admin_panel" class="home_btn">Назад</a>
</div>
</body>
</html>

