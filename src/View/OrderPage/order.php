<?php
/**
 * @var Bicycle $bicycle ;
 */

use App\Model\Bicycle;

?>
<div class="order_content">
	<a href="/detail/<?= $bicycle->getId() ?>">
		<div class="item_card_order">
			<img
				src="<?= "/resources/product/img/{$bicycle->getId()}.{$bicycle->getName()}/{$bicycle->getMainImageName()}" ?>"
				alt="" class="item_img_order">
			<p class="item_title"><?= $bicycle->getName() ?></p>
			<div class="line"></div>
			<p class="item_price"><?= $bicycle->getPrice() ?> ₽</p>
		</div>
	</a>
	<div class="errors">
		<?php if (!empty($errors)): ?>
			<?php foreach ($errors as $error): ?>
				<div>
					<?php foreach ($error as $errorName): ?>
						<?= $errorName; ?>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<div class="form_inner">
		<form action="/order/confirm" class="order_form" method="post">
			<p>Ваш номер телефона</p>
			<p><input type="number" name="number" id="" class="order_input" maxlength="11" required></p>
			<p>Ваш адрес</p>
			<p><input type="text" name="address" id="" class="order_input" required></p>
			<button class="order_btn">Оформить заказ</button>
		</form>
	</div>
</div>

