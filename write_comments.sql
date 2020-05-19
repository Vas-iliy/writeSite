create table comments
(
    id_comment int auto_increment
        primary key,
    id_login   int                                 not null,
    comment    varchar(500)                        not null,
    time       timestamp default CURRENT_TIMESTAMP not null,
    moder      varchar(10)                         null,
    newTime    timestamp default CURRENT_TIMESTAMP null
);

