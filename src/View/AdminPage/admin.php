<?php
/**
 * @var CategoryList $objectList ;
 * @var Category $object ;
 * @var int $pagesCount;
 */

use App\Model\Category;
use App\Model\CategoryList;
use Core\Database\Repo\AdminPanelRepo;

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="/resources/css/reset.css">
	<link rel="stylesheet" href="/resources/css/style.css">
	<title>admin</title>
</head>
<body>
<div class="admin_content">
	<a href="/" class="sign_in_btn">Главная</a>
	<a href="/sign_out" class="sign_in_btn">Выйти</a>
	<a href="/admin_panel/dev_reset" class="sign_in_btn">Откат БД</a>
	<div class="tab" id="tab-1">
		<div class="tab_nav">
			<?php foreach ($objectList as $object): ?>
				<button type="button" class="tab-btn <?=$object->getID() === '0'?'tab-btn-active':''?>"
						data-target-id="<?=$object->getID()?>">
					<?=$object->getName()?>
				</button>
			<?php endforeach; ?>
		</div>
		<div class="tab-content">
			<?php foreach ($objectList as $object): ?>
				<div class="tab-pane <?=$object->getID() === '0'? 'tab-pane-show':''?>" data-id="<?=$object->getID()?>">
					<table class="table_inner">
						<thead>
						<tr>
							<?php foreach (AdminPanelRepo::getItemColumns($object->getEngName()) as $field): ?>
								<th><?=$field?></th>
							<?php endforeach; ?>
							<th>Действие
								<br>
								<a href="/admin_panel/add_form"
								   class="add_btn <?=($object->getEngName()!=='item')?'disable':'active'?>">
									Добавить
								</a>
							</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach (AdminPanelRepo::getItemList($object->getEngName()) as $item):?>
							<tr>
								<?php foreach ($item as $itemValue): ?>
									<td><?=$itemValue?></td>
								<?php endforeach; ?>
								<td>
									<a href="/admin_panel/edit?id=<?=$item[0]?>&table=<?=$object->getEngName()?>">
										Изменить
									</a>
									<a href="/admin_panel/delete?id=<?=$item[0]?>"
									   class="<?=($object->getEngName()!=='item')?'disable':'active'?>"
									   onclick="return window.confirm('Удалить этот объект?');">
										Удалить
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

</div>
<div class="pages">
	<?php for ($pageNum = 1; $pageNum <= $pagesCount; $pageNum++): ?>
		<a href="/admin_panel/<?= $pageNum === 1 ? '' : '?page='.$pageNum ?>" class="page_number"><?= $pageNum ?></a>
	<?php endfor; ?>
</div>
</body>
<script src="/resources/js/lightbox2-2.11.4/dist/js/lightbox-plus-jquery.js"></script>
<script src="/resources/js/script.js"></script>
</html>
