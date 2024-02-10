<?php
/**
 * @var \App\Model\Bicycle $bicycle
 */
?>

<div class="main_content_inner">
	<div class="content">
		<div class="image_container">
			<a href="<?="/resources/product/img/{$bicycle->getId()}.{$bicycle->getName()}/{$bicycle->getMainImageName()}"?>" data-lightbox="roadtrip" class="poster_link"><img src="<?="/resources/product/img/{$bicycle->getId()}.{$bicycle->getName()}/{$bicycle->getMainImageName()}"?>" alt="" class="poster"></a>
			<div class="lower_images">
				<?php foreach ($bicycle->getImages() as $image):?>
					<a href="<?="/resources/product/img/{$bicycle->getId()}.{$bicycle->getName()}/{$image->getName()}"?>" data-lightbox="roadtrip"><img src="<?="/resources/product/img/{$bicycle->getId()}.{$bicycle->getName()}/{$image->getName()}"?>" alt="" class="lower_poster"></a>
				<?php endforeach;?>
			</div>
		</div>
		<div class="right_col">
			<div class="headline">
				<p class="main_title"><?=$bicycle->getName()?></p>
			</div>
			<div class="line"></div>
			<p class="about">О товаре</p>
			<div class="info">
				<ul>
					<li class="ul_left_col">Стоимость</li>
					<li class="ul_left_col">Стоимость</li>
					<li class="ul_left_col">Стоимость</li>
				</ul>
				<ul>
					<li class="ul_right_col">500$</li>
					<li class="ul_right_col">500$</li>
					<li class="ul_right_col">500$</li>
				</ul>
			</div>
			<div class="item_tags">
				<a href="/" class="item_tag">Велосипед</a>
				<a href="/" class="item_tag">Спортивный</a>
				<a href="/" class="item_tag">Горный</a>
				<a href="/" class="item_tag">Для детей</a>
			</div>
			<a href="/OrderPage/order"><button class="order_btn" type="submit">Заказать</button></a>
			<p class="description_title">Описание товара</p>
			<p class="description"><?=$bicycle->getDescription()?></p>
		</div>
	</div>
</div>
