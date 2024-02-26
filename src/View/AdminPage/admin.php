<?php
/**
 * @var CategoryList $objectList ;
 * @var Category $object ;
 * @var int $page ;
 * @var int $pagesCount
 * @var string $tableName ;
 * @var string $title ;
 */

use App\Model\Category;
use App\Model\CategoryList;
use Core\Database\Repo\AdminPanelRepo;
use App\Service\ViewService;
use App\Config\Config;

if (!isset($tableName))
{
	$tableName = '';
}
$itemList = AdminPanelRepo::getItemList($tableName, $page);
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
<div class="admin_content">
	<div class="buttons">
		<a href="/" class="admin_panel_btn">Главная</a>
		<a href="/sign_out" class="admin_panel_btn">Выйти</a>
		<a href="/admin_panel/dev_reset" class="admin_panel_btn">Откат БД</a>
	</div>
	<div class="tab">
		<div class="tab_nav">
			<?php foreach ($objectList as $object): ?>
				<a href="/admin_panel/<?= $object->getEngName() ?>/"
				   class="tab-btn <?= ($tableName === $object->getEngName()) ? 'category_active' : '' ?>">
					<?= $object->getName() ?>
				</a>
			<?php endforeach; ?>
		</div>
		<div class="tab-content">
			<div class="gear <?= $tableName !== '' ? 'disable' : 'active' ?>">
				<div class="gear_img_inner">
					<img src="resources/img/cog-solid.svg" alt="" class="gear_img">
				</div>
				<p class="gear_text">Выберите таблицу слева для просмотра и редактирования сущностей</p>
			</div>
			<div class="tab-pane <?= $tableName !== '' ? 'tab-pane-show' : 'disable' ?>"
				 data-id="<?= $object->getId() ?>">
				<table class="table_inner">
					<thead>
					<tr>
						<?php foreach (AdminPanelRepo::getItemColumns($tableName) as $field): ?>
							<th><?= $field ?></th>
						<?php endforeach; ?>
						<th>Действие
							<br>
							<div class="add_line <?= ($tableName !== 'item') ? 'disable' : 'active' ?>">
								<img src="/resources/img/plus-solid.svg" alt="" class="add_img">
								<a href="/admin_panel/<?= $tableName ?>/add_form"
								   class="add_btn">
									Добавить
								</a>
							</div>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($itemList as $item): ?>
						<tr>
							<?php foreach ($item as $itemValue): ?>
								<td><?= ViewService::truncate($itemValue, (new Config())->option('TEXT_TRUNCATE')) ?></td>
							<?php endforeach; ?>
							<td>
								<div class="edit_line">
									<img src="/resources/img/edit-solid.svg" alt="" class="edit_img">
									<a href="/admin_panel/<?= $tableName ?>/edit?id=<?= $item['id'] ?>">
										Изменить
									</a>
								</div>
								<div class="delete_line <?= ($tableName !== 'item') ? 'disable' : 'active' ?>">
									<img src="/resources/img/trash-alt-solid.svg" alt="" class="delete_img">
									<a href="/admin_panel/<?= $tableName ?>/delete?id=<?= $item['id'] ?>"
									   class="delete_btn"
									   onclick="return window.confirm('Удалить этот объект?');">
										Удалить
									</a>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="pages">
				<a href="/admin_panel/<?= $tableName ?>/"
				   class="page_number <?= (!isset($page) || $page == '1') ? 'disable' : 'active' ?>">
					<img src="/resources/img/home-solid.svg" alt="" class="arrow">
				</a>
				<a href="/admin_panel/<?= $tableName ?>/?page=<?= (!isset($page)) ? '1' : ($page - 1) ?>"
				   class="page_number <?= (!isset($page) || $page == '1') ? 'disable' : 'active' ?>">
					<img src="/resources/img/arrow-left-solid.svg" alt="" class="arrow">
				</a>
				<a href="/admin_panel/<?= $tableName ?>/?page=<?= (!isset($page)) ? '2' : ($page + 1) ?>"
				   class="page_number <?= ($page >= $pagesCount) ? 'disable' : 'active' ?>">
					<img src="/resources/img/arrow-right-solid.svg" alt="" class="arrow">
				</a>
			</div>
		</div>

	</div>
</div>

</body>
<script src="/resources/js/lightbox2-2.11.4/dist/js/lightbox-plus-jquery.js"></script>
<script src="/resources/js/script.js"></script>
</html>
