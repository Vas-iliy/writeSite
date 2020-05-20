create table admin
(
    id_admin       int auto_increment
        primary key,
    admin_login    varchar(30) not null,
    admin_password varchar(30) not null,
    constraint admin_login_uindex
        unique (admin_login)
);

