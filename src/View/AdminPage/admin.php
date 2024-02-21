<?php
/**
 * @var CategoryList $objectList ;
 * @var Category $object ;
 * @var int $page;
 * @var int $pagesCount
 * @var string $tableName;
 * @var string $title;
 */

use App\Model\Category;
use App\Model\CategoryList;
use Core\Database\Repo\AdminPanelRepo;
use App\Service\ViewService;
use App\Config\Config;

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="/resources/css/reset.css">
	<link rel="stylesheet" href="/resources/css/style.css">
	<title><?=$title?></title>
</head>
<body>
<div class="admin_content">
	<div class="buttons">
		<a href="/" class="sign_in_btn">Главная</a>
		<a href="/sign_out" class="sign_in_btn">Выйти</a>
		<a href="/admin_panel/dev_reset" class="sign_in_btn">Откат БД</a>
	</div>
	<div class="tab" id="tab-1">
		<div class="tab_nav">
			<?php foreach ($objectList as $object): ?>
				<a href="/admin_panel/<?=$object->getEngName()?>/"
				   class="tab-btn">
					<?=$object->getName()?>
				</a>
			<?php endforeach; ?>
		</div>
		<div class="tab-content">
			<div class="tab-pane <?=$tableName !== '' ? 'tab-pane-show':'disable'?>" data-id="<?=$object->getID()?>">
				<table class="table_inner">
					<thead>
					<tr>
						<?php foreach (AdminPanelRepo::getItemColumns($tableName) as $field): ?>
							<th><?=$field?></th>
						<?php endforeach; ?>
						<th>Действие
							<br>
							<a href="/admin_panel/<?=$tableName?>/add_form"
							   class="add_btn <?=($tableName!=='item')?'disable':'active'?>">
								Добавить
							</a>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach (AdminPanelRepo::getItemList($tableName, $page) as $item):?>
						<tr>
							<?php foreach ($item as $itemValue): ?>
								<td><?=ViewService::truncate((string)$itemValue, (new Config())->option('TEXT_TRUNCATE'))?></td>
							<?php endforeach; ?>
							<td>
								<a href="/admin_panel/<?=$tableName?>/edit?id=<?=$item[0]?>">
									Изменить
								</a>
								<a href="/admin_panel/<?=$tableName?>/delete?id=<?=$item[0]?>"
								   class="<?=($tableName!=='item')?'disable':'active'?>"
								   onclick="return window.confirm('Удалить этот объект?');">
									Удалить
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="pages">
			<a href="/admin_panel/<?=$tableName?>/?page=1"
			   class="page_number <?=(!isset($page) || ($page) =='1')? 'disable':'active'?>">
				В начало
			</a>
			<a href="/admin_panel/<?=$tableName?>/?page=<?= (!isset($page))?'1':($page-1)?>"
			   class="page_number <?=(!isset($page) || ($page) =='1')? 'disable':'active'?>">
				Назад
			</a>
			<a href="/admin_panel/<?=$tableName?>/?page=<?= (!isset($page))?'2':($page+1)?>"
			   class="page_number <?=(($tableName === '') || $page === $pagesCount)? 'disable':'active'?>">
				Вперёд
			</a>
		</div>
	</div>
</div>

</body>
<script src="/resources/js/lightbox2-2.11.4/dist/js/lightbox-plus-jquery.js"></script>
<script src="/resources/js/script.js"></script>
</html>
