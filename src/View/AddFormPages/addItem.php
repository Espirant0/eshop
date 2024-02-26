<?php
/**
 * @var string $tableName ;
 * @var string $title ;
 */

use Core\Database\Repo\AdminPanelRepo;

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
<div class="add_container">
	<div class="auth_errors">
		<?php if (!empty($errors)): ?>
			<div>
				<?php foreach ($errors as $field => $messages): ?>
					<div>
						<?php foreach ($messages as $message): ?>
							<p><?= $message ?></p>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="form_container">
		<form action="/admin_panel/<?= $tableName ?>/add" method="post" enctype="multipart/form-data" class="add_form">
			<div class="add_form_inner">
				<input multiple type="file" name="files[]" id="img_input" class="img_input">
				<div class="text_input">
					<label>
						<input type="text" name="title" class="login_input auth_input" placeholder="Введите название"
							   required>
					</label>
					<label>
						<input type="number" name="price" class="login_input auth_input" placeholder="Введите цену"
							   required>
					</label>
					<label>
						<input type="text" name="description" class="password_input auth_input"
							   placeholder="Введите описание" required>
					</label>
					<label>
						<input type="number" name="create_year" class="login_input auth_input"
							   placeholder="Введите год выпуска" required>
					</label>
					<label>
						<input type="number" name="speed" class="login_input auth_input"
							   placeholder="Введите количество скоростей" required>
					</label>
				</div>
				<div class="selects">
					<label for="category">Выберите категорию:</label>
					<select name="category">
						<?php foreach (AdminPanelRepo::getItemList('category') as $item): ?>
							<option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
						<?php endforeach; ?>
					</select>
					<label for="color_id">Выберите цвет:</label>
					<select name="color_id">
						<?php foreach (AdminPanelRepo::getItemList('color') as $item): ?>
							<option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
						<?php endforeach; ?>
					</select>
					<label for="material_id">Выберите материал:</label>
					<select name="material_id">
						<?php foreach (AdminPanelRepo::getItemList('material') as $item): ?>
							<option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
						<?php endforeach; ?>
					</select>
					<label for="status">Выберите статус:</label>
					<select name="status">
						<option value="1">Доступен</option>
						<option value="0">Скрыт</option>
					</select>
					<label for="manufacturer_id">Выберите аудиторию:</label>
					<select name="target_id">
						<?php foreach (AdminPanelRepo::getItemList('target_audience') as $item): ?>
							<option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
						<?php endforeach; ?>
					</select>
					<label for="manufacturer_id">Выберите производителя:</label>
					<select name="manufacturer_id">
						<?php foreach (AdminPanelRepo::getItemList('manufacturer') as $item): ?>
							<option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<button class="auth_btn">Добавить</button>
		</form>
	</div>
	<a href="/admin_panel" class="home_btn">Назад</a>
</div>
</body>
</html>

