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

INSERT INTO `write`.images (id_img, id_state, id_login, image_title, extension) VALUES (16, 43, 12, '_LvRPamQJXc', 'jpg');
INSERT INTO `write`.images (id_img, id_state, id_login, image_title, extension) VALUES (17, 43, 12, '_MeoCB_amg', 'jpg');
INSERT INTO `write`.images (id_img, id_state, id_login, image_title, extension) VALUES (18, 43, 12, '_MyyorXEq', 'jpg');
INSERT INTO `write`.images (id_img, id_state, id_login, image_title, extension) VALUES (19, 43, 12, '_PJqeVgEcgk', 'jpg');
INSERT INTO `write`.images (id_img, id_state, id_login, image_title, extension) VALUES (20, 43, 12, '_vXMG_U-Y', 'jpg');
INSERT INTO `write`.images (id_img, id_state, id_login, image_title, extension) VALUES (21, 43, 12, '_XgoSUYuk', 'jpg');
INSERT INTO `write`.images (id_img, id_state, id_login, image_title, extension) VALUES (22, 43, 12, '_YVGiAVNE', 'jpg');
INSERT INTO `write`.images (id_img, id_state, id_login, image_title, extension) VALUES (23, 43, 12, '_ZXZzaYdnCU', 'jpg');