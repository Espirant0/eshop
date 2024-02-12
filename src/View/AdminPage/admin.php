<?php
/**
 * @var array $bicycleList ;
 * @var array $categoryList ;
 * @var array $buttonList ;
 * @var array $colorList ;
 * @var array $targetList;
 * @var array $userList;
 * @var array $orderList;
 * @var array $manufacturerList;
 * @var array $materialList;
 * @var Bicycle $bicycle ;
 * @var Category $category ;
 * @var Category $button ;
 * @var User $user ;
 * @var Order $order;
 */

use App\Model\Bicycle;
use App\Model\Category;
use App\Model\Order;
use App\Model\User;

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
	<title>admin</title>
</head>
<body>
<div class="admin_content">
	<a href="/" class="sign_in_btn">Главная</a>
	<a href="/sign_out" class="sign_in_btn">Выйти</a>
	<div class="tab" id="tab-1">
		<div class="tab_nav">
			<button type="button" class="tab-btn tab-btn-active" data-target-id="0">Товары</button>
			<?php foreach ($buttonList as $button): ?>
				<button type="button" class="tab-btn" data-target-id="<?=$button->getID()?>"><?=$button->getName()?></button>
			<?php endforeach; ?>
		</div>
		<div class="tab-content">
			<div class="tab-pane tab-pane-show" data-id="0">
				<table class="table_inner">
					<thead>
					<tr>
						<th>ID</th>
						<th>Название</th>
						<th>Цвет</th>
						<th>Год выпуска</th>
						<th>Материал</th>
						<th>Цена</th>
						<th>Описание</th>
						<th>Статус</th>
						<th>Производитель</th>
						<th>Действие
							<br>
							<a href="/admin_panel/add_form" class="add_btn">Добавить</a>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($bicycleList as $bicycle): ?>
						<tr>
							<td><?= $bicycle->getId() ?></td>
							<td><?= $bicycle->getName() ?></td>
							<td><?= $bicycle->getColor() ?></td>
							<td><?= $bicycle->getYear() ?></td>
							<td><?= $bicycle->getMaterial() ?></td>
							<td><?= $bicycle->getPrice() ?></td>
							<td><?= $bicycle->getDescription() ?></td>
							<td><?= $bicycle->getStatus() ?></td>
							<td><?= $bicycle->getVendor() ?></td>
							<td>
								<a href="/admin_panel/edit?id=<?= $bicycle->getId()?>&table=item">Изменить</a>
								<a href="/admin_panel/delete?id=<?= $bicycle->getId() ?>"
								   onclick="return window.confirm('Удалить этот объект?');">Удалить</a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" data-id="1">
				<table class="table_inner">
					<thead>
					<tr>
						<th>ID</th>
						<th>Название категории</th>
						<th>Название категории (eng)</th>
						<th>Действие
							<br>
							<!--<a href="/admin_panel/add_form" class="add_btn">Добавить</a>-->
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($categoryList as $category): ?>
						<tr>
							<td><?= $category->getId() ?></td>
							<td><?= $category->getName() ?></td>
							<td><?= $category->getEngName() ?></td>
							<td>
								<a href="/admin_panel/edit?id=<?=$category->getId()?>&table=category">Изменить</a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" data-id="2">
				<table class="table_inner">
					<thead>
					<tr>
						<th>ID</th>
						<th>Название цвета</th>
						<th>Название цвета (eng)</th>
						<th>Действие
							<br>
							<!--<a href="/admin_panel/add_form" class="add_btn">Добавить</a>-->
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($colorList as $color): ?>
						<tr>
							<td><?= $color['id'] ?></td>
							<td><?= $color['name']?></td>
							<td><?= $color['engName']?></td>
							<td>
								<a href="/admin_panel/edit?id=<?=$color['id']?>&table=color">Изменить</a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" data-id="3">
				<table class="table_inner">
					<thead>
					<tr>
						<th>ID</th>
						<th>Производитель</th>
						<th>Действие
							<br>
							<!--<a href="/admin_panel/add_form" class="add_btn">Добавить</a>-->
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($manufacturerList as $manufacturer): ?>
						<tr>
							<td><?= $manufacturer['id'] ?></td>
							<td><?= $manufacturer['name']?></td>
							<td>
								<a href="/admin_panel/edit?id=<?=$manufacturer['id']?>&table=manufacturer">Изменить</a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" data-id="4">
				<table class="table_inner">
					<thead>
					<tr>
						<th>ID</th>
						<th>Название материала</th>
						<th>Название материала (eng)</th>
						<th>Действие
							<br>
							<!--<a href="/admin_panel/add_form" class="add_btn">Добавить</a>-->
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($materialList as $material): ?>
						<tr>
							<td><?= $material['id'] ?></td>
							<td><?= $material['name']?></td>
							<td><?= $material['engName']?></td>
							<td>
								<a href="/admin_panel/edit?id=<?=$material['id']?>&table=material">Изменить</a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" data-id="5">
				<table class="table_inner">
					<thead>
					<tr>
						<th>ID</th>
						<th>Название товара</th>
						<th>Статус</th>
						<th>Дата заказа</th>
						<th>Цена</th>
						<th>Телефон покупателя</th>
						<th>Адрес доставки</th>
						<th>Действие
							<br>
							<!--<a href="/admin_panel/add_form" class="add_btn">Добавить</a>-->
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($orderList as $order): ?>
						<tr>
							<td><?= $order->getOrderId()?></td>
							<td><?= $order->getItemName()?></td>
							<td><?= $order->getStatus()?></td>
							<td><?= $order->getCreateDate()?></td>
							<td><?= $order->getPrice()?></td>
							<td><?= $order->getNumber()?></td>
							<td><?= $order->getDeliveryAddress()?></td>
							<td>
								<a href="/admin_panel/edit?id=<?=$order->getOrderId()?>&table=orders">Изменить</a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" data-id="6">
				<table class="table_inner">
					<thead>
					<tr>
						<th>ID</th>
						<th>Название таргета</th>
						<th>Название таргета (eng)</th>
						<th>Действие
							<br>
							<!--<a href="/admin_panel/add_form" class="add_btn">Добавить</a>-->
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($targetList as $target): ?>
						<tr>
							<td><?= $target['id'] ?></td>
							<td><?= $target['name']?></td>
							<td><?= $target['engName']?></td>
							<td>
								<a href="/admin_panel/edit?id=<?=$target['id']?>&table=target_audience">Изменить</a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" data-id="7">
				<table class="table_inner">
					<thead>
					<tr>
						<th>Телефон</th>
						<th>Имя</th>
						<th>Роль</th>
						<th>Адрес</th>
						<th>Пароль</th>
						<th>Действие
							<br>
							<!--<a href="/admin_panel/add_form" class="add_btn">Добавить</a>-->
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($userList as $user): ?>
						<tr>
							<td><?= $user->getTelephone()?></td>
							<td><?= $user->getName()?></td>
							<td><?= $user->getRole()?></td>
							<td><?= $user->getAddress()?></td>
							<td><?= $user->getPassword()?></td>
							<td>
								<a href="/admin_panel/edit?id=<?=$user->getTelephone()?>&table=user">Изменить</a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</body>
<script src="/resources/js/lightbox2-2.11.4/dist/js/lightbox-plus-jquery.js"></script>
<script src="/resources/js/script.js"></script>
</html>
