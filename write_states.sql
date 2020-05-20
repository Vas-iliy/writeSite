create table states
(
    id_state      int auto_increment
        primary key,
    id_login      int                                 not null,
    id_cat        int                                 not null,
    state_title   varchar(30)                         not null,
    state_content varchar(8000)                       not null,
    time          timestamp default CURRENT_TIMESTAMP not null,
    state_moder   varchar(10)                         null,
    state_newTime timestamp default CURRENT_TIMESTAMP not null,
    constraint states_cats_id_cat_fk
        foreign key (id_cat) references bd.cats (id_cat),
    constraint states_registrations_id_login_fk
        foreign key (id_login) references registrations (id_login)
);

INSERT INTO `write`.states (id_state, id_login, id_cat, state_title, state_content, time, state_moder, state_newTime) VALUES (1, 12, 1, 'Статья про спорт', 'Тут написано про спорт', '2020-05-20 18:02:13', 'yes', '2020-05-20 18:02:13');