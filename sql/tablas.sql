create table autores(
    id_autor int auto_increment primary key,
    apellidos varchar(60) not null,
    nombre varchar(40) not null
);
create table libros(
    id_libro int auto_increment primary key,
    titulo varchar(80) not null,
    isbn varchar(13), 
    autor int,
    portada varchar(80) default "/img/default.jpg",
    constraint lib_autor foreign key(autor) references autores(id_autor) on update cascade on delete cascade
);