<?php
/**
 * @var \App\Model\Bicycle[] $bicycleList
 * @var  $category_name
 */

?>
<div class="cards">
	<?php foreach ($bicycleList as $bicycle):?>
	<?php if($bicycle->getCategories()[0]->getEngName()==$category_name || $category_name==null):?>
	<a href="/Detail/<?=$bicycle->getId()?>">
		<div class="item_card">
			<img src="<?="/resources/product/img/{$bicycle->getId()}.{$bicycle->getName()}/{$bicycle->getMainImageName()}"?>" alt="" class="item_img">
			<p class="item_title"><?=$bicycle->getName()?></p>
			<div class="line"></div>
			<p class="item_price"><?= $bicycle->getPrice().' Р'?></p>
		</div>
	</a>
	<?php endif; endforeach;?>
</div>
<div class="pages">
	<a href="" class="first page_number">1</a>
	<a href="" class="second page_number">2</a>
	<p class="page_number">...</p>
	<a href="" class="last page_number">6</a>
</div>