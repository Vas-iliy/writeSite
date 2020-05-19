create table admin_images
(
    id_admin   int         not null,
    id_img     int         not null,
    permission varchar(10) null,
    primary key (id_admin, id_img),
    constraint admin_images_admin_id_admin_fk
        foreign key (id_admin) references admin (id_admin),
    constraint admin_images_images_id_img_fk
        foreign key (id_img) references images (id_img)
);

