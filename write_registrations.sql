create table registrations
(
    id_login       int auto_increment
        primary key,
    userName       varchar(30)                          not null,
    surname        varchar(30)                          not null,
    country        varchar(30)                          not null,
    email          varchar(30)                          not null,
    login          varchar(30)                          not null,
    userPassword   varchar(30)                          not null,
    userTime       timestamp  default CURRENT_TIMESTAMP not null,
    authKey        varchar(30)                          not null,
    userValidation tinyint(1) default 0                 not null,
    newTime        timestamp  default CURRENT_TIMESTAMP not null,
    userActive     tinyint(1) default 0                 not null,
    constraint registrations_email_uindex
        unique (email),
    constraint registrations_login_uindex
        unique (login)
);

INSERT INTO `write`.registrations (id_login, userName, surname, country, email, login, userPassword, userTime, authKey, userValidation, newTime, userActive) VALUES (9, 'l', 'l', 'l', 'k@f', 'h', 'l', '2020-05-19 16:45:34', 'SR7y8NPgQ35c0ieWwBe7', 0, '2020-05-19 16:45:34', 0);
INSERT INTO `write`.registrations (id_login, userName, surname, country, email, login, userPassword, userTime, authKey, userValidation, newTime, userActive) VALUES (12, 'Василий', 'Колясев', 'Россия', 'vkolyasev1999@mail.ru', 'Vasiliy', '123', '2020-05-19 16:51:23', 'CMJFGHUsovj64aAKW5t0', 1, '2020-05-19 16:51:37', 0);
INSERT INTO `write`.registrations (id_login, userName, surname, country, email, login, userPassword, userTime, authKey, userValidation, newTime, userActive) VALUES (16, 'Василий', 'hjb', 'Россия', 'kkolyasev1228@gmail.com', 'Vasiliy1', '111', '2020-05-21 12:12:07', 'JizS0y8Snsq63LwABe3i', 1, '2020-05-21 12:12:24', 0);
INSERT INTO `write`.registrations (id_login, userName, surname, country, email, login, userPassword, userTime, authKey, userValidation, newTime, userActive) VALUES (18, 'kiry', 'hjb', 'j', 'vasilykolyasev@yandex.ru', 'vkolyasev1999', '5', '2020-05-21 12:16:56', '8BL89FUA7f6327VMeOvq', 1, '2020-05-21 12:18:10', 0);