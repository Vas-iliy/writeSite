create table states
(
    id_state   int auto_increment
        primary key,
    id_login   int                                 not null,
    id_cat     int                                 not null,
    id_comment int                                 not null,
    title      varchar(30)                         not null,
    content    varchar(8000)                       not null,
    time       timestamp default CURRENT_TIMESTAMP not null,
    moder      varchar(10)                         null,
    newTime    timestamp default CURRENT_TIMESTAMP not null,
    constraint states_cats_id_cat_fk
        foreign key (id_cat) references bd.cats (id_cat),
    constraint states_comments_id_comment_fk
        foreign key (id_comment) references comments (id_comment),
    constraint states_registrations_id_login_fk
        foreign key (id_login) references registrations (id_login)
);

