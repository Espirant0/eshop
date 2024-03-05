INSERT INTO status (name)
VALUES ('В обработке'),
       ('Отправлен'),
       ('Доставлен');

UPDATE user SET id = '70000000000' WHERE name = 'BaseUser';

UPDATE user SET id = '79999999999' WHERE name = 'Admin';

alter table item
    modify status int null;