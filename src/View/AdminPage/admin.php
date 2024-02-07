<?php
/**
 * @var array $itemList ;
 * @var Bicycle $item ;
*/

use App\Model\Bicycle;
use Core\Database\Repo\AdminPanelRepo;
?>
<div class="admin_content">
	<a href="/sign_out" class="sign_in_btn">Выйти</a>
	<div class="tab" id="tab-1">
		<div class="tab_nav">
			<button type="button" class="tab-btn tab-btn-active" data-target-id="0">Товары</button>
			<button type="button" class="tab-btn" data-target-id="1">Категории</button>
			<button type="button" class="tab-btn" data-target-id="2">Заказы</button>
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
					</tr>
					</thead>
					<tbody>
					<?php foreach ($itemList as $item):?>
					<tr>
						<td><?=$item->getId()?></td>
						<td><?=$item->getName()?></td>
						<td><?=$item->getColor()?></td>
						<td><?=$item->getYear()?></td>
						<td><?=$item->getMaterial()?></td>
						<td><?=$item->getPrice()?></td>
						<td><?=$item->getDescription()?></td>
						<td><?=$item->getStatus()?></td>
						<td><?=$item->getVendor()?></td>
					</tr>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" data-id="1">
				<table class="table_inner">
					<thead>
					<tr>
						<th>ID</th>
						<th>Название категории</th>
					</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" data-id="2">
				<table class="table_inner">
					<thead>
					<tr>
						<th>ID</th>
						<th>Название товара</th>
						<th>Статус</th>
						<th>Дата заказа</th>
						<th>Цена</th>
						<th>Номер телефона</th>
						<th>Адрес</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td>1</td>
						<td>Товар 1</td>
						<td>В обработке</td>
						<td>2022-01-01</td>
						<td>1000</td>
						<td>1234567890</td>
						<td>Адрес 1</td>
					</tr>
					<tr>
						<td>2</td>
						<td>Товар 2</td>
						<td>Доставлен</td>
						<td>2022-01-02</td>
						<td>2000</td>
						<td>9876543210</td>
						<td>Адрес 2</td>
					</tr>
					</tbody>
				</table></div>
		</div>
	</div>

</div>
