create table images
(
    id_img      int auto_increment
        primary key,
    id_state    int         not null,
    id_login    int         not null,
    image_title varchar(30) not null,
    extension   varchar(10) not null,
    constraint images_registrations_id_login_fk
        foreign key (id_login) references registrations (id_login),
    constraint images_states_id_state_fk
        foreign key (id_state) references states (id_state)
);

INSERT INTO `write`.images (id_img, id_state, id_login, image_title, extension) VALUES (1, 1, 12, '_6bIniVXdXs', 'jpg');