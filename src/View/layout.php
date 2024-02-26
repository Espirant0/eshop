<?php
/**
 * @var string $content
 * @var Category $category
 * @var CategoryList $categoryList
 * @var string $categoryName
 * @var string $title ;
 */

use App\Model\Category;
use App\Model\CategoryList;
use App\Service\AuthService;

$isAuthorized = AuthService::checkAuth();
if (!isset($categoryName))
{
	$categoryName = '';
}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="icon" href="/resources/img/icon.png" type="image/x-icon">
	<link rel="stylesheet" href="/resources/css/reset.css">
	<link rel="stylesheet" href="/resources/css/style.css">
	<link rel="stylesheet" href="/resources/js/lightbox2-2.11.4/dist/css/lightbox.css"/>
	<title><?= $title ?></title>
</head>
<body>
<div class="grid">
	<div class="menu">
		<div class="menu_container">
			<ul class="tags">
				<a href="/">
					<li>
						<p>Главная</p>
					</li>
				</a>
				<?php foreach ($categoryList as $category): ?>
					<a href="/category/<?= $category->getEngName() ?>/">
						<li <?= ($categoryName === $category->getEngName()) ? 'class="category_active"' : '' ?>>
							<span>
								<?= $category->getName(); ?>
							</span>
						</li>
					</a>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="top_line">
		<div class="logo_inner">
			<a href="/"><img src="/resources/img/logo.png" alt="" class="logo"></a>
		</div>
		<div class="search_line">
			<div class="input__line">
				<div class="search_img_inner">
					<img src="/resources/img/search-solid.svg" alt="" class="search_img">
				</div>
				<form action="/search.php?find">
					<input type="search" placeholder="Поиск по каталогу" class="input_main" name="search" id="search">
					<input type="submit" formaction="/?search" class="search_btn" value="Искать">
				</form>
			</div>
			<div class="account">
				<div class="account_btn">
					<img src="/resources/img/user-circle-regular.svg" alt="" class="account_img" onclick="dropdown()">
				</div>
				<div class="account_links" id="dropdown">
					<a href="/auth" class="sign_in_btn <?= $isAuthorized ? 'disable' : 'active' ?>">Войти</a>
					<a href="/sign_out" class="sign_in_btn <?= $isAuthorized ? 'active' : 'disable' ?>">Выйти</a>
					<a href="/admin_panel" class="admin_btn">Админ-панель</a>
				</div>
			</div>
		</div>
	</div>
	<div class="main_section">
		<?= $content ?>
	</div>
</div>
</body>
<script src="/resources/js/lightbox2-2.11.4/dist/js/lightbox-plus-jquery.js"></script>
<script src="/resources/js/script.js"></script>
</html>