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
        foreign key (id_cat) references cats (id_cat),
    constraint states_registrations_id_login_fk
        foreign key (id_login) references registrations (id_login)
);

INSERT INTO `write`.states (id_state, id_login, id_cat, state_title, state_content, time, state_moder, state_newTime) VALUES (43, 12, 28, 'Статья про аниме', 'тут написано про нарисованных девочек и конечно же про еблю в сракотан', '2020-05-23 12:42:46', 'yes', '2020-05-22 23:46:00');