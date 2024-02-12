<?php
/**
 * @var Bicycle $item;
 * @var Bicycle $CategoryList;
 */

use App\Model\Bicycle;
?>

<div class="main_content_inner">
	<div class="content">
		<div class="image_container">
			<a href="<?="/resources/product/img/{$item->getId()}.{$item->getName()}/{$item->getMainImageName()}"?>" data-lightbox="roadtrip" class="poster_link"><img src="<?="/resources/product/img/{$item->getId()}.{$item->getName()}/{$item->getMainImageName()}"?>" alt="" class="poster"></a>
			<div class="lower_images">
				<?php foreach ($item->getImages() as $image):?>
					<a href="<?="/resources/product/img/{$item->getId()}.{$item->getName()}/{$image->getName()}"?>" data-lightbox="roadtrip"><img src="<?="/resources/product/img/{$item->getId()}.{$item->getName()}/{$image->getName()}"?>" alt="" class="lower_poster"></a>
				<?php endforeach;?>
			</div>
		</div>
		<div class="right_col">
			<div class="headline">
				<p class="main_title"><?=$item->getName()?></p>
			</div>
			<div class="line"></div>
			<p class="about">О товаре</p>
			<div class="info">
				<ul>
					<li class="ul_left_col">Стоимость</li>
					<li class="ul_left_col">Производитель</li>
					<li class="ul_left_col">Год производства</li>
					<li class="ul_left_col">Скоростей</li>
				</ul>
				<ul>
					<li class="ul_right_col"><?=$item->getPrice()?></li>
					<li class="ul_right_col"><?=$item->getVendor()?></li>
					<li class="ul_right_col"><?=$item->getYear()?></li>
					<li class="ul_right_col"><?=$item->getSpeed()?></li>
				</ul>
			</div>
			<div class="item_tags">
				<?php foreach($item->getCategories() as $category):?>
				<a href="/<?="?find={$category->getID()}:{$category->getEngName()}"?>" class="item_tag"><?=$category->getName()?></a>
				<?php endforeach;?>
			</div>
			<a href="/OrderPage/order/<?=$item->getId()?>"><button class="order_btn" type="submit">Заказать</button></a>
			<p class="description_title">Описание товара</p>
			<p class="description"><?=$item->getDescription()?></p>
		</div>
	</div>
</div>
