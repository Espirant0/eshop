ALTER TABLE user
ADD COLUMN login VARCHAR(255);

UPDATE user
SET login = id
WHERE id = 79999999999;

UPDATE user
SET login = id
WHERE id = 70000000000;

alter table orders
drop foreign key orders_ibfk_3;

ALTER TABLE user
DROP PRIMARY KEY;

ALTER TABLE user
ADD PRIMARY KEY (id);

UPDATE user
SET id = 1
WHERE id = 70000000000;

UPDATE user
SET id = 2
WHERE id = 79999999999;

ALTER TABLE user
MODIFY COLUMN id INT AUTO_INCREMENT UNIQUE;

ALTER TABLE user
DROP PRIMARY KEY;

ALTER TABLE user
ADD PRIMARY KEY (login);

ALTER TABLE orders
MODIFY COLUMN user_id varchar(255);

ALTER TABLE orders
add foreign key (user_id) references user(login);