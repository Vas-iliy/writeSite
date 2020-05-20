create table cats
(
    id_cat    int auto_increment
        primary key,
    cat_title varchar(30) not null
);

INSERT INTO `write`.cats (id_cat, cat_title) VALUES (1, 'Спорт');