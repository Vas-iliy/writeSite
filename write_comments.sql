create table comments
(
    id_comment      int auto_increment
        primary key,
    id_login        int                                   not null,
    id_state        int                                   not null,
    com             varchar(500)                          not null,
    tm              timestamp   default CURRENT_TIMESTAMP not null,
    comment_moder   varchar(10) default '0'               not null,
    comment_newTime timestamp   default CURRENT_TIMESTAMP null,
    login           varchar(30)                           not null,
    constraint comments_registrations_id_login_fk
        foreign key (id_login) references registrations (id_login),
    constraint comments_states_id_state_fk
        foreign key (id_state) references states (id_state)
);

