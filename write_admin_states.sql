create table admin_states
(
    id_admin   int         not null,
    id_state   int         not null,
    permission varchar(10) null,
    primary key (id_admin, id_state),
    constraint admin_states_admin_id_admin_fk
        foreign key (id_admin) references admin (id_admin),
    constraint admin_states_states_id_state_fk
        foreign key (id_state) references bd.states (id_state)
);

