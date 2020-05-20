create table admin_comments
(
    id_admin           int         not null,
    id_comment         int         not null,
    comment_permission varchar(10) null,
    primary key (id_admin, id_comment),
    constraint admin_comments_admin_id_admin_fk
        foreign key (id_admin) references admin (id_admin),
    constraint admin_comments_comments_id_comment_fk
        foreign key (id_comment) references comments (id_comment)
);

