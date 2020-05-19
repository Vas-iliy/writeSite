create table images
(
    id_img    int auto_increment
        primary key,
    id_login  int         not null,
    name      varchar(30) not null,
    extension varchar(10) not null,
    constraint images_registrations_id_login_fk
        foreign key (id_login) references registrations (id_login)
);

