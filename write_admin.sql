create table admin
(
    id_admin int auto_increment
        primary key,
    login    varchar(30) not null,
    password varchar(30) not null,
    constraint admin_login_uindex
        unique (login)
);

