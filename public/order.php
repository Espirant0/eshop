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
		<div class="order_content">
			<a href="detail.php">
				<div class="item_card_order">
					<img src="resources/img/item.jpg" alt="" class="item_img_order">
					<p class="item_title">MacBook Pro</p>
					<div class="line"></div>
					<p class="item_price">1000$</p>
				</div>
			</a>
			<div class="form_inner">
				<form action="confirmed.php" class="order_form">
					<p>Ваше ФИО</p>
					<p><input type="text" name="name" id="" class="order_input" required></p>
					<p>Ваш номер телефона</p>
					<p><input type="text" name="phone" id="" class="order_input" maxlength="11" required></p>
					<p>Ваш адрес</p>
					<p><input type="text" name="address" id="" class="order_input" required></p>
					<a href="confirmed.php"><button type="submit" class="order_btn">Оформить заказ</button></a>
			</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>
