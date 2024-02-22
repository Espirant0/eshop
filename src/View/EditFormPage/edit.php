<?php
/**
 * @var int $itemId;
 * @var string $tableName;
 * @var string $title;
 * @var array $item;
 */


use App\Cache\FileCache;
use Core\Database\Repo\AdminPanelRepo;

$item = AdminPanelRepo::getItemById($tableName, $itemId);
$table = (new FileCache())->get($tableName);
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
		<form action="/admin_panel/<?=$tableName?>/update?id=<?=$itemId;?>" method="post" class="auth_form">
			<?php $value = 0; foreach ($table as $field):?>
				<label>
					<?=$field?>
				</label>
				<input type="text" name="<?=$field?>" class="password_input auth_input" value="<?=$item[$value]?>">
			<?php $value++?>
			<?php endforeach; ?>
			<button class="auth_btn">Обновить</button>
		</form>
	</div>
	<a href="/admin_panel" class="home_btn">Назад</a>
</div>
</body>
</html>

