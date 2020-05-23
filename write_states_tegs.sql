create table states_tegs
(
    id       int auto_increment
        primary key,
    id_state int not null,
    id_teg   int not null,
    constraint states_tegs_states_id_state_fk
        foreign key (id_state) references states (id_state),
    constraint states_tegs_tegs_id_teg_fk
        foreign key (id_teg) references tegs (id_teg)
);

INSERT INTO `write`.states_tegs (id, id_state, id_teg) VALUES (1, 43, 40);
INSERT INTO `write`.states_tegs (id, id_state, id_teg) VALUES (2, 43, 41);
INSERT INTO `write`.states_tegs (id, id_state, id_teg) VALUES (3, 43, 42);
INSERT INTO `write`.states_tegs (id, id_state, id_teg) VALUES (4, 43, 40);
INSERT INTO `write`.states_tegs (id, id_state, id_teg) VALUES (5, 43, 41);
INSERT INTO `write`.states_tegs (id, id_state, id_teg) VALUES (6, 43, 42);
INSERT INTO `write`.states_tegs (id, id_state, id_teg) VALUES (7, 43, 43);