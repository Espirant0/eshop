<?php
/**
 * @var int $itemId ;
 * @var string $tableName ;
 * @var string $title ;
 * @var array $item ;
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
	<title><?= $title ?></title>
</head>
<body>
<div class="auth_container">
	<div class="auth_errors">
		<?php if (!empty($errors)):?>
			<?php foreach ($errors as $error): ?>
				<div>
					<?php foreach ($error as $errorName): ?>
						<?= $errorName; ?>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<div class="edit_inner">
		<div class="form_container">
			<form action="/admin_panel/<?= $tableName ?>/update?id=<?= $itemId; ?>" method="post" class="auth_form">
				<?php foreach ($table as $field): ?>
					<label>
						<?= $field ?>
					</label>
					<input type="text" name="<?= $field ?>" class="edit_input" value="<?= $item[$field] ?>">
				<?php endforeach; ?>
				<button class="auth_btn">Обновить</button>
			</form>
		</div>
	</div>
	<a href="/admin_panel" class="home_btn">Назад</a>
</div>
</body>
</html>

