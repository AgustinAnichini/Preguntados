create database if not exists sabiondos;
use sabiondos;
DROP TABLE IF EXISTS Usuarios, pregunta, categoria, preguntaUsuario, preguntaPartida, partida,respuesta;
CREATE TABLE usuarios (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          nombre_completo VARCHAR(100) NOT NULL,
                          fecha_nacimiento DATE NOT NULL,
                          sexo ENUM('Masculino', 'Femenino', 'Prefiero no cargarlo') NOT NULL,
                          contrasenia VARCHAR(255) NOT NULL,
                          token_validacion VARCHAR(255),
                          mail VARCHAR(255) NOT NULL UNIQUE,
                          pais VARCHAR(50) NOT NULL,
                          ciudad VARCHAR(50) NOT NULL,
                          foto_perfil VARCHAR(255),
                          nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
                          fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          cuenta_activa BOOLEAN DEFAULT FALSE,
                          ranking int,
                          puntajeMasAlto int

);

ALTER TABLE usuarios MODIFY COLUMN ranking INT;
ALTER TABLE usuarios MODIFY COLUMN puntajeMasAlto INT;


INSERT INTO usuarios (
    nombre_completo,
    fecha_nacimiento,
    sexo,
    contrasenia,
    token_validacion,
    mail,
    pais,
    ciudad,
    foto_perfil,
    nombre_usuario,
    fecha_registro,
    cuenta_activa,
    ranking,
    puntajeMasAlto
) VALUES (
             'no hay tu tia',
             '1985-06-15',
             'Masculino',
             'noHayTuTia',
             'tokenTuTia',
             'juanPerez@gmail.com',
             'Argentina',
             'Buenos Aires',
             'path/to/profile/photo.jpg',
             'noHayTuTia',
             '2023-06-15',
             true,
             500,
             150
         );

create table categoria(
                          id INT PRIMARY KEY not null,
                          tipo varchar(30) not null,
                          color varchar(30)
);

INSERT INTO categoria (id,tipo, color) VALUES
                                           (1,'historia', 'marron'),
                                           (2,'deporte', 'verde'),
                                           (3,'geografia', 'azul'),
                                           (4,'entretenimiento', 'violeta'),
                                           (5,'arte', 'rojo');

create table pregunta(
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         id_categoria int not null,
                         texto varchar(200) not null,
                         nivel_dificultad varchar(50) not null,
                         valor int not null,
                         tiempo_respuesta int not null,

                         foreign key(id_categoria) references categoria(id)
);


INSERT INTO pregunta (id_categoria, texto, nivel_dificultad, valor, tiempo_respuesta) VALUES
                                                                                          (1, '¿Quién fue el primer presidente de los Estados Unidos?', 'Fácil', 10, 30), #1
                                                                                          (2, '¿En qué año se celebraron los primeros Juegos Olímpicos modernos?', 'Medio', 20, 30), #ID 5
                                                                                          (3, '¿Cuál es la capital de Francia?', 'Fácil', 10, 30), #ID 9
                                                                                          (4, '¿Cuál es el nombre del villano en la película "El Rey León"?', 'Fácil', 10, 30), #ID 13
                                                                                          (5, '¿Quién pintó la Mona Lisa?', 'Medio', 20, 30); #17


create table respuesta (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           texto varchar(200),
                           pregunta_id int not null,
                           correcta boolean not null,

                           foreign key(pregunta_id)  references pregunta(id)
);


INSERT INTO respuesta (texto, pregunta_id, correcta) VALUES
                                                         -- Respuestas para la pregunta 1
                                                         ('George Washington', 1, true),
                                                         ('Thomas Jefferson', 1, false),
                                                         ('Abraham Lincoln', 1, false),
                                                         ('John Adams', 1, false),

                                                         -- Respuestas para la pregunta 2
                                                         ('1896', 2, true),
                                                         ('1900', 2, false),
                                                         ('1920', 2, false),
                                                         ('1912', 2, false),

                                                         -- Respuestas para la pregunta 3
                                                         ('París', 3, true),
                                                         ('Londres', 3, false),
                                                         ('Madrid', 3, false),
                                                         ('Berlín', 3, false),
                                                         -- Respuestas para la pregunta 4
                                                         ('Scar', 4, true),
                                                         ('Mufasa', 4, false),
                                                         ('Simba', 4, false),
                                                         ('Nala', 4, false),

                                                         -- Respuestas para la pregunta 5
                                                         ('Leonardo da Vinci', 5, true),
                                                         ('Michelangelo', 5, false),
                                                         ('Raphael', 5, false),
                                                         ('Donatello', 5, false);


create table partida (
                         id int auto_increment primary key,
                         idUsuario int not null,
                         tiempo float not null,
                         puntaje int not null,
                         duracion int not null,
                         preguntasAcertadas int,

                         foreign key (idUsuario) references usuarios(id)
);


create table preguntaPartida (
                                 id int auto_increment primary key,
                                 idPregunta int not null,
                                 idPartida int not null,
                                 foreign key(idPregunta) references pregunta(id),
                                 foreign key(idPartida) references partida(id)
);

create table preguntaUsuario (
                                 id int auto_increment primary key,
                                 idPregunta int not null,
                                 idUsuario int not null,
                                 foreign key(idPregunta) references pregunta(id),
                                 foreign key(idUsuario) references usuarios(id)
);
use sabiondos;
SELECT * FROM preguntaUsuario;