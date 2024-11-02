# RORECETS

## ESTRUCTURA

// 20241028 creada la estructura

rorecets/
├── app/
│   ├── controllers/
│   │   ├── session_controller.php
│   │   ├── router.php
│   │   ├── register.php
│   │   └── login.php
│   ├── models/
│   │   └── User.php
│   ├── views/
│   │   ├── home/
│   │   │   └── index.php
│   │   └── layouts/
│   │       └── main.php
│   └── core/
│       ├── Controller.php
│       ├── Model.php
│       └── View.php
├── config/
│   └── config.php
├── public/
│   ├── css/
│   │   └── styles.css
│   ├── js/
│   │   └── scripts.js
│   └──  index.php
└──.env

## IDEA

// 20241027 pensada la idea

Al trabajar con angular en la Dual he decidido hacer la estructura de esta aplicación parecida a cómo sería con este framework.

También he decidido implementar en la estructura lo que viene a ser los modulos, pero en vez de separarlos por componentes, he decidio estructurarlos por el estándar MVC(Modelo Vista Controlador).

## EXTRAS

- CONTROLADORES:
-- Router -> Controlador que me permite manejar rutas

## SQL

// 20241102 diseñado y creado el sql (va a ir cambiando dependiendo las necesidades)

create table users (
    id_user int primary key auto_increment,
    name varchar(50) not null,
    email varchar(100) unique not null,
    password varchar(255) not null,
    registration_date timestamp default current_timestamp
);

create table recipes (
    id_recipe int primary key auto_increment,
    id_user int not null,
    title varchar(100) not null,
    description text,
    instructions text,
    publication_date timestamp default current_timestamp,
    foreign key (id_user) references users(id_user) on delete cascade
);

create table ingredients (
    id_ingredient int primary key auto_increment,
    name varchar(100) unique not null
);

create table recipes_ingredients (
    id_recipe_ingredient int primary key auto_increment,
    id_recipe int not null,
    id_ingredient int not null,
    quantity decimal(5, 2) not null,
    unit varchar(50),
    foreign key (id_recipe) references recipes(id_recipe) on delete cascade,
    foreign key (id_ingredient) references ingredients(id_ingredient) on delete cascade,
    unique (id_recipe, id_ingredient)
);

create table comments (
    id_comment int primary key auto_increment,
    id_recipe int not null,
    id_user int not null,
    text text not null,
    comment_date timestamp default current_timestamp,
    foreign key (id_recipe) references recipes(id_recipe) on delete cascade,
    foreign key (id_user) references users(id_user) on delete cascade
);

create table ratings (
    id_rating int primary key auto_increment,
    id_recipe int not null,
    id_user int not null,
    score tinyint check (score between 1 and 5),
    rating_date timestamp default current_timestamp,
    foreign key (id_recipe) references recipes(id_recipe) on delete cascade,
    foreign key (id_user) references users(id_user) on delete cascade,
    unique (id_recipe, id_user)
);

create table followers (
    id_follower int not null,
    id_followed int not null,
    follow_date timestamp default current_timestamp,
    primary key (id_follower, id_followed),
    foreign key (id_follower) references users(id_user) on delete cascade,
    foreign key (id_followed) references users(id_user) on delete cascade
);
