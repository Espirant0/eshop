<?php
/**
 * @var Bicycle $item;
 */

use App\Model\Bicycle;

?>
<div class="order_content">
	<a href="/Detail/<?=$item->getId()?>">
		<div class="item_card_order">
			<img src="<?="/resources/product/img/{$item->getId()}.{$item->getName()}/{$item->getMainImageName()}"?>" alt="" class="item_img_order">
			<p class="item_title"><?=$item->getName()?></p>
			<div class="line"></div>
			<p class="item_price"><?=$item->getPrice()?></p>
		</div>
	</a>
	<div class="form_inner">
		<form action="/order/confirm/<?=$item->getId()?>" class="order_form" method="post">
			<!--<p>Ваше ФИО</p>
			<p><input type="text" name="name" id="" class="order_input" required></p>-->
			<p>Ваш номер телефона</p>
			<p><input type="number" name="number" id="" class="order_input" maxlength="11" required></p>
			<p>Ваш адрес</p>
			<p><input type="text" name="address" id="" class="order_input" required></p>
			<button class="order_btn">Оформить заказ</button>
	</div>
	</form>
</div>

