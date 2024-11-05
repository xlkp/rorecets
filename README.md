# RORECETS

## ESTRUCTURA

// 20241028 creada la estructura

``` shell
rorecets/
├──app/
│   ├── controllers/
│   │   ├── session_controller.php
│   │   ├── register.php
│   │   └── login.php
│   ├── models/
│   │   └── User.php
│   └── views/
│       ├── home/
│       │   └── index.php
│       ├── auth/
│       │   ├── login.html
│       │   └── register.html
│       └── layouts/
│           ├── 404.php
│           └── admin.php
├──config/
│   └── config.php
├──public/
│   ├── css/
│   │   └── main.css
│   ├── js/
│   │   └── main.js
│   └── index.php
├──router.php
└──.htaccess

```

## IDEA

// 20241027 pensada la idea

Al trabajar con angular en la Dual he decidido hacer la estructura de esta aplicación parecida a cómo sería con este framework.

También he implementado en la estructura lo que viene a ser los modulos, pero en vez de separarlos por componentes, los he estructurado por el estándar MVC(Modelo Vista Controlador).

## EXTRAS

// 20241104 cambiada manera de usar el router

- Router con .htaccess:
-- La manera más sencilla sin un framework en php es con el .htaccess, me he comido demasiado código basura de StackOverflow y guías fuera de mí nivel para hacer un controlador de rutas simple por lo que me he metido a fondo a investigar sobre el htaccess y me ha encantado ya que es mucho más intuitivo que andar instalando laravel o mirandome un artículo que no me explica algo que entienda, aparte de que utiliza expresiones regulares que se me dan genial.

-- He redirigido todas las rutas al index.php, que hace de router para el resto de las rutas, esto lo he conseguido mediante el .htaccess y definiendo las rutas relativas a los controladores hacia su directorio y fichero respectivo para tener unas rutas más user friendly.

Esta guía : <https://www.educative.io/answers/how-to-create-a-basic-php-router> me ha venido de lujo para lograr esto.

## SQL

// 20241102 diseñado y creado el sql (va a ir cambiando dependiendo las necesidades)

```sql
create table users (
    id_user int primary key auto_increment,
    name varchar(50) not null,
    email varchar(100) unique not null,
    pwd varchar(255) not null,
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
    quantity int not null,
    unit varchar(50),
    foreign key (id_recipe) references recipes(id_recipe) on delete cascade,
    foreign key (id_ingredient) references ingredients(id_ingredient) on delete cascade,
    unique (id_recipe, id_ingredient)
);

create table comments (
    id_comment int primary key auto_increment,
    id_recipe int not null,
    id_user int not null,
    description text not null,
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
