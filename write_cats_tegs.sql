create table cats_tegs
(
    id_cat int not null,
    id_teg int not null,
    primary key (id_cat, id_teg),
    constraint cats_tegs_cats_id_cat_fk
        foreign key (id_cat) references bd.cats (id_cat),
    constraint cats_tegs_tegs_id_teg_fk
        foreign key (id_teg) references bd.tegs (id_teg)
);

