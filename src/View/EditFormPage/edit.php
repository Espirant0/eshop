<?php
/**
 * @var int $itemId;
 * @var string $table;
 */

use App\Cache\FileCache;

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
		<form action="/admin_panel/update?id=<?=$itemId;?>&table=<?=$table?>" method="post" class="auth_form">
			<?php foreach ((new FileCache())->get($table) as $field):?>
				<label>
					<input type="text" name="<?=$field?>" class="password_input auth_input" placeholder="Введите <?=$field?>">
				</label>
			<?php endforeach;?>
			<button class="auth_btn">Обновить</button>
		</form>
	</div>
	<a href="/admin_panel" class="home_btn">Назад</a>
</div>
</body>
</html>

