<?php
/**
 * @var Bicycle[] $bicycleList
 * @var string $categoryName;
 * @var string $page;
 * @var int $pagesCount
 */

use App\Model\Bicycle;

?>
<div class="cards">
	<?php foreach ($bicycleList as $bicycle):?>
	<a href="/detail/<?=$bicycle->getId()?>">
		<div class="item_card">
			<img src="<?="/resources/product/img/{$bicycle->getId()}.{$bicycle->getName()}/{$bicycle->getMainImageName()}"?>" alt="" class="item_img">
			<p class="item_title"><?=$bicycle->getName()?></p>
			<div class="line"></div>
			<p class="item_price"><?= $bicycle->getPrice().' Р'?></p>
		</div>
	</a>
	<?php endforeach;?>
</div>
<div class="pages">
	<a href="/<?=($categoryName !== '')? 'category/'.$categoryName.'/' : ''?>?page=1"
	   class="page_number <?=(!isset($page) || $page =='1')? 'disable':'active'?>">
		В начало
	</a>
	<a href="/<?=($categoryName !== '')? 'category/'.$categoryName.'/' : ''?>?page=<?= (!isset($page))?'1':((int)$page-1)?>"
	   class="page_number <?=(!isset($page) || $page == '1')? 'disable':'active'?>">
		Назад
	</a>
	<a href="/<?=($categoryName !== '')? 'category/'.$categoryName.'/' : ''?>?page=<?= (!isset($page))?'2':((int)$page+1)?>"
	   class="page_number <?=((int)$page === $pagesCount)? 'disable':'active'?>">
		Вперёд
	</a>
</div>