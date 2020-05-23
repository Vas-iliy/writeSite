create table tegs
(
    id_teg    int auto_increment
        primary key,
    teg_title varchar(30) not null
);

INSERT INTO `write`.tegs (id_teg, teg_title) VALUES (40, 'Лоли');
INSERT INTO `write`.tegs (id_teg, teg_title) VALUES (41, 'Зубик');
INSERT INTO `write`.tegs (id_teg, teg_title) VALUES (42, 'Чулочки');
INSERT INTO `write`.tegs (id_teg, teg_title) VALUES (43, 'Аниме');