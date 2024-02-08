alter table user
    add password varchar(255) not null;

alter table item
    add speed int(3) default 1,
    drop column color,
    drop column material,
    add material_id INT,
    add color_id INT,
    add target_id INT default 3,
    add foreign key (material_id) REFERENCES material(id),
    add foreign key (color_id) REFERENCES color(id),
    add foreign key (target_id) REFERENCES target_audience(id);

alter table category
    add engName VARCHAR(50);

alter table image
    drop column width,
    drop column height,
    add column ord int(3) not null;