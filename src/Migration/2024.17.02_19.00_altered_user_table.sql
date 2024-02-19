alter table user
    drop foreign key user_ibfk_1;

alter table orders
    drop foreign key orders_ibfk_3;

delete from user where id != 'null';

alter table user
    modify id decimal(12) not null,
    add foreign key (role_id) references role(id);

alter table orders
    modify user_id decimal(12),
    add foreign key (user_id) references user(id);

insert into user(id, name, role_id, address, password)
values (79999999999, 'Admin', 1, 'Админская улица, 999', 'password'),
       (70000000000,'BaseUser',2,'Улица рядового пользователя, 0','1234');