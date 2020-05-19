create table registrations
(
    id_login   int auto_increment
        primary key,
    name       varchar(30)                         not null,
    surname    varchar(30)                         not null,
    country    varchar(30)                         not null,
    email      varchar(30)                         not null,
    login      varchar(30)                         not null,
    password   varchar(30)                         not null,
    time       timestamp default CURRENT_TIMESTAMP not null,
    validation int       default 0                 not null,
    newTime    timestamp default CURRENT_TIMESTAMP not null,
    constraint registrations_email_uindex
        unique (email),
    constraint registrations_login_uindex
        unique (login)
);

