INSERT INTO manufacturer (name) VALUES
	('Trek'),
	('Specialized'),
	('Giant'),
	('Cannondale');


INSERT INTO category (name) VALUES
	('Горный'),
	('Шоссейный'),
	('Гибридный'),
	('Электрический');


INSERT INTO role (name) VALUES
	('Администратор'),
	('Пользователь');


INSERT INTO status (name) VALUES
	('Подтвержден'),
	('Отправлен'),
	('Отменен'),
	('Доставлен');



INSERT INTO user (id, name, role_id, address) VALUES
	('123456789', 'Василий Васильев', 2, 'Советский проспект 5'),
	('987654321', 'Петр Попов', 2, 'Тельмана');



INSERT INTO item (title, color, create_year, material, price, description, status, manufacturer_id)
VALUES
	('Trek Fuel EX 8', 'Черный', 2023, 'Carbon', 2999, 'Full suspension mountain bike', true, 1),
	('Specialized Roubaix', 'Красный', 2022, 'Carbon', 2499, 'Endurance road bike', true, 2),
	('Giant Escape 3', 'Синий', 2023, 'Aluminum', 399, 'Hybrid commuter bike', true, 3),
	('Cannondale Synapse Neo', 'Синий', 2023, 'Carbon', 3499, 'Electric road bike', true, 4);




INSERT INTO image (item_id, is_main, height, width) VALUES
	(1, true, 800, 600),
	(2, true, 800, 600),
	(3, true, 800, 600),
	(4, true, 800, 600);


INSERT INTO items_category (item_id, category_id) VALUES
	(1, 1),
	(2, 2),
	(3, 3),
	(4, 2);



INSERT INTO orders (item_id, status_id, data_create, price, user_id, address) VALUES
	(1, 1, '2024-01-01', 2999, '123456789', 'Железнодорожная'),
	(2, 2, '2024-01-02', 2499, '987654321', 'Красная');