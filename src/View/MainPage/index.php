<?php
/**
 * @var Bicycle[] $bicycleList
 * @var string $categoryName;
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
	<a href="/<?=($categoryName !== '')? 'category/'.$categoryName.'/' : ''?>?page=1" class="page_number <?=(!isset($_GET['page']) || ($_GET['page'])==='1')? 'disable':'active'?>">В начало</a>
	<a href="/<?=($categoryName !== '')? 'category/'.$categoryName.'/' : ''?>?page=<?= (!isset($_GET['page']))?'1':($_GET['page']-1)?>" class="page_number <?=(!isset($_GET['page']) || ($_GET['page'])==='1')? 'disable':'active'?>">Назад</a>
	<a href="/<?=($categoryName !== '')? 'category/'.$categoryName.'/' : ''?>?page=<?= (!isset($_GET['page']))?'2':($_GET['page']+1)?>" class="page_number">Вперёд</a>
</div>