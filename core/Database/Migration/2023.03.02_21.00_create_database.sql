CREATE TABLE IF NOT EXISTS manufacturer -- таблица производители
(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255)
);


CREATE TABLE IF NOT EXISTS item -- таблица товар
(
	id INT AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(255),
	color VARCHAR(50),
	create_year INT,
	material VARCHAR(100),
	price INT,
	description VARCHAR(900),
	status BOOLEAN,
	manufacturer_id INT,
	FOREIGN KEY (manufacturer_id) REFERENCES manufacturer(id)
	);


CREATE TABLE IF NOT EXISTS image -- таблица картинка
(
	id INT AUTO_INCREMENT PRIMARY KEY,
	item_id INT,
	is_main BOOLEAN,
	height INT,
	width INT,
	FOREIGN KEY (item_id) REFERENCES item(id)
	);

CREATE TABLE IF NOT EXISTS category -- таблица категория
(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL
	);


CREATE TABLE IF NOT EXISTS items_category -- таблица товар_категория
(
	item_id INT,
	category_id INT,
	FOREIGN KEY (item_id) REFERENCES item(id),
	FOREIGN KEY (category_id) REFERENCES category(id),
	PRIMARY KEY (item_id, category_id)
	);


CREATE TABLE IF NOT EXISTS status -- таблица статус
(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255)
	);


CREATE TABLE IF NOT EXISTS role -- таблица роль
(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255)
	);


CREATE TABLE IF NOT EXISTS user -- таблица пользователи
(
	id VARCHAR(255) PRIMARY KEY, -- Номер телефона пользователя в качестве первичного ключа
	name VARCHAR(255),
	role_id INT,
	address VARCHAR(255),
	FOREIGN KEY (role_id) REFERENCES role(id)
	);


CREATE TABLE IF NOT EXISTS orders -- таблица заказы
(
	id INT AUTO_INCREMENT PRIMARY KEY,
	item_id INT,
	status_id INT,
	data_create DATE,
	price INT,
	user_id VARCHAR(255), -- предположим, что user_id это номер телефона пользователя
	address VARCHAR(255),
	FOREIGN KEY (item_id) REFERENCES item(id),
	FOREIGN KEY (status_id) REFERENCES status(id),
	FOREIGN KEY (user_id) REFERENCES user(id)
	);