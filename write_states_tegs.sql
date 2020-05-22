create table states_tegs
(
    id_state int not null,
    id_teg   int not null,
    primary key (id_state, id_teg),
    constraint states_tegs_states_id_state_fk
        foreign key (id_state) references states (id_state),
    constraint states_tegs_tegs_id_teg_fk
        foreign key (id_teg) references tegs (id_teg)
);

INSERT INTO `write`.states_tegs (id_state, id_teg) VALUES (43, 36);
INSERT INTO `write`.states_tegs (id_state, id_teg) VALUES (43, 37);
INSERT INTO `write`.states_tegs (id_state, id_teg) VALUES (43, 38);
INSERT INTO `write`.states_tegs (id_state, id_teg) VALUES (43, 39);