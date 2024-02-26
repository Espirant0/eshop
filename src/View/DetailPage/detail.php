<?php
/**
 * @var Bicycle $bicycle ;
 * @var Bicycle $CategoryList ;
 */

use App\Model\Bicycle;

?>

<div class="main_content_inner">
	<div class="content">
		<div class="image_container">
			<a href="<?= "/resources/product/img/{$bicycle->getId()}.{$bicycle->getName()}/{$bicycle->getMainImageName()}" ?>"
			   data-lightbox="roadtrip" class="poster_link">
				<img
					src="<?= "/resources/product/img/{$bicycle->getId()}.{$bicycle->getName()}/{$bicycle->getMainImageName()}" ?>"
					alt=""
					class="poster"
				>
			</a>
			<div class="lower_images">
				<?php foreach ($bicycle->getImages() as $image): ?>
					<a href="<?= "/resources/product/img/{$bicycle->getId()}.{$bicycle->getName()}/{$image->getName()}" ?>"
						<?= $image->isMain() ? '' : 'data-lightbox="roadtrip"' ?>
					   class="<?= $image->isMain() ? 'disable' : 'active' ?>">
						<img
							src="<?= "/resources/product/img/{$bicycle->getId()}.{$bicycle->getName()}/{$image->getName()}" ?>"
							alt=""
							class="lower_poster"
						>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="right_col">
			<div class="headline">
				<p class="main_title"><?= $bicycle->getName() ?></p>
			</div>
			<div class="detail_line"></div>
			<p class="about">О товаре</p>
			<div class="info">
				<ul class="ul_left">
					<li class="ul_left_col">Стоимость</li>
					<li class="ul_left_col">Производитель</li>
					<li class="ul_left_col">Год производства</li>
					<li class="ul_left_col">Скоростей</li>
				</ul>
				<ul class="ul_right">
					<li class="ul_right_col"><?= $bicycle->getPrice() ?> ₽</li>
					<li class="ul_right_col"><?= $bicycle->getVendor() ?></li>
					<li class="ul_right_col"><?= $bicycle->getYear() ?></li>
					<li class="ul_right_col"><?= $bicycle->getSpeed() ?></li>
				</ul>
			</div>
			<div class="item_tags">
				<?php foreach ($bicycle->getCategories() as $category): ?>
					<a href="/<?= "?{$category->getId()}={$category->getEngName()}" ?>"
					   class="item_tag"><?= $category->getName() ?></a>
				<?php endforeach; ?>
			</div>
			<a href="/order">
				<button class="order_btn" type="submit">Заказать</button>
			</a>
			<p class="description_title">Описание товара</p>
			<p class="description"><?= $bicycle->getDescription() ?></p>
		</div>
	</div>
</div>