<?php
/** @var array $content */
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="resources/css/reset.css">
	<link rel="stylesheet" href="resources/css/style.css">
	<title>Main</title>
</head>
<body>
<div class="grid">
	<div class="menu">
		<div class="menu_container">
			<ul class="tags">
				<a href="index.php"><li><p>Главная</p></li></a>
				<a href=""><li><p>Электроника</p></li></a>
				<a href=""><li><p>Электроника</p></li></a>
				<a href=""><li><p>Электроника</p></li></a>
				<a href=""><li><p>Электроника</p></li></a>
				<a href=""><li><p>Электроника</p></li></a>
			</ul>
		</div>
	</div>
	<div class="top_line">
		<div class="search_line">
			<div class="input__line">
				<input type="text" placeholder="Поиск по каталогу" class="input_main">
			</div>
			<button class="search_btn">Искать</button>
		</div>
	</div>
	<div class="main_section">
		<?=$content?>
	</div>
</div>
</body>
</html>
