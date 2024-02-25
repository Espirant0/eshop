<?php
/**
 * @var Bicycle[] $bicycleList ;
 * @var string $categoryName ;
 * @var string $page ;
 * @var int $pagesCount ;
 * @var string $httpQuery ;
 */

use App\Model\Bicycle;

?>
<div class="cards">
	<?php foreach ($bicycleList as $bicycle): ?>
		<a href="/detail/<?= $bicycle->getId() ?>">
			<div class="item_card">
				<img
					src="<?= "/resources/product/img/{$bicycle->getId()}.{$bicycle->getName()}/{$bicycle->getMainImageName()}" ?>"
					alt="" class="item_img">
				<p class="item_title"><?= $bicycle->getName() ?></p>
				<div class="line"></div>
				<p class="item_price"><?= $bicycle->getPrice() . ' ₽' ?></p>
			</div>
		</a>
	<?php endforeach; ?>
</div>
<div class="pages">
	<a href="/?<?= $httpQuery ?>"
	   class="page_number <?= (!isset($page) || $page == '1') ? 'disable' : 'active' ?>">
		<img src="/resources/img/home-solid.svg" alt="" class="arrow">
	</a>
	<a href="/?<?= $httpQuery ?>&page=<?= (!isset($page)) ? '1' : ((int)$page - 1) ?>"
	   class="page_number <?= (!isset($page) || $page == '1') ? 'disable' : 'active' ?>">
		<img src="/resources/img/arrow-left-solid.svg" alt="" class="arrow">
	</a>
	<a href="/?<?= $httpQuery ?>&page=<?= (!isset($page)) ? '2' : ((int)$page + 1) ?>"
	   class="page_number <?= ((int)$page >= $pagesCount) ? 'disable' : 'active' ?>">
		<img src="/resources/img/arrow-right-solid.svg" alt="" class="arrow">
	</a>
</div>