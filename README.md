# RORECETS

## ESTRUCTURA

// 20241028 creada la estructura

``` shell
rorecets/
├── app/
│   ├── controllers/
│   │   ├── admin_controller.php
│   │   ├── auth_controller.php
│   │   ├── comment_controller.php
│   │   ├── pagination_controller.php
│   │   ├── profile_controller.php
│   │   └── recipes_controller.php
│   ├── models/
│   │   └── Recipes.php
│   └── views/
│       ├── auth/
│       │   ├── 404.html
│       │   ├── change_password.html
│       │   ├── login.html
│       │   ├── noAdmin.html
│       │   ├── noLogin.html
│       │   └── register.html
│       ├── recipes/
│       │   ├── admin_recipes.php
│       │   ├── edit_recipe.php
│       │   ├── recipes.php
│       │   └── view_recipe.php
│       └── user/
│           ├── admin.php
│           ├── edit_profile.php
│           ├── followers.php
│           └── user.php
├── assets/
│   └── img/
│       ├── auth-bg.jpg
│       └── recipes/
├── config/
│   └── config.php
├── public/
│   └── index.php
├── var/
│   ├── lock/
│   ├── log/
│   └── run/
├── .htaccess
├── apache2.conf
├── docker-compose.yml
├── PHP.Dockerfile
└── rorecets.sql
```

## IDEA

// 20241027 pensada la idea

Al trabajar con angular en la Dual he decidido hacer la estructura de esta aplicación parecida a cómo sería con este framework.

También he implementado en la estructura lo que viene a ser los modulos, pero en vez de separarlos por componentes, los he estructurado por el estándar MVC(Modelo Vista Controlador).

## EXTRAS

//20241201 añadido seguir a usuarios, quitar seguidores, etc (currada padre)

// Imágenes related

Cuando el usuario borra una receta en el servidor tambien se borra la imagen.

// 20241104 cambiada manera de usar el router

- Router con .htaccess para redireccionar a inicio:

- La manera más sencilla sin un framework en php es con el .htaccess, me he comido demasiado código basura de StackOverflow, ChatGPT y guías fuera de mí nivel para hacer un controlador de rutas simple por lo que me he metido a fondo a investigar sobre el htaccess y me ha encantado ya que es mucho más intuitivo que andar instalando laravel o mirandome un artículo que no me explica algo que entienda, aparte de que utiliza expresiones regulares que se me dan genial.

- He redirigido todas las rutas al router.php, que hace de router para el resto de las rutas, esto lo he conseguido mediante el .htaccess y definiendo las rutas relativas a los controladores hacia su directorio y fichero respectivo para tener unas rutas más user friendly.

- Esta guía : <https://www.educative.io/answers/how-to-create-a-basic-php-router> me ha venido de lujo para lograr esto.

// 20241107 creado protector de rutas

- PROTECTOR DE RUTAS
- Protego las rutas más sensibles con variables de sesión para evitar que por ejemplo entren a admin y me borren usuarios o recetas

// 20241128 Cambios en las rutas

- Ignora las rutas con parámetros get en la cabecera, para permitir el uso de este método.

// El usuario puede seleccionar su foto de perfil de una selección ya predifinida

## SQL

// 20241102 diseñado y creado el sql (va a ir cambiando dependiendo las necesidades)

```sql
create table users (
    id_user int primary key auto_increment,
    username varchar(50) unique not null,
    email varchar(100) unique not null,
    pwd varchar(255) not null,
    exp varchar(100) not null,
    is_admin tinyint (1) default 0,
    registration_date timestamp default current_timestamp
);

create table recipes (
    id_recipe int primary key auto_increment,
    id_user int not null,
    recipe_type varchar(20),
    title varchar(100) not null,
    description text,
    instructions text,
    -- fácil, media, difícil
    difficulty tinyint check (difficulty between 1 and 3),
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
    -- unidad de medida, g, kg, ml, l, cucharadas, cucharaditas, tazas
    unit tinyint check (unit between 1 and 7),
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

```

## POBLATION

// USUARIOS

- Alice: Alice123
- Bob: Bob2024
- Charlie: Char2024
- Diana: Diana456
- Edward: Edw5678
- Fiona: Fiona890
- George: Geor1234
- Hannah: Hann4567
- Ian: Ian8901
- Julia: Jul2345
- Kevin: Kevi678
- Linda: Lind789
- Michael: Micha345
- Nora: Nora567
- Oliver: Oliv123

## USE CASES

- unregistered user : login, register, see home page.
- registered user: login, close session, see all recipes, comment on them and follow other users, delete own recipes.
- admin: login, close session, delete or make users admin too, delete recipes.

## SQL MODIFICATIONS OR INSERTIONS

``` sql
-- password is literaly root hashed
insert into users (username, pwd, email, exp, is_admin) VALUES ('master', '$2b$12$qCZuRjvq1qzyaaCkfyMbceqQ/vVT.tAZuvjYUjFnJ33hSZ1F.P9rS', 'master@xlkp.com', 'creator of many pot holes in history', 1);

-- added route for the recipe image 
ALTER TABLE recipes
ADD COLUMN image_recipe VARCHAR(255) DEFAULT 'default.png';

```
