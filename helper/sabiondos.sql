create database if not exists sabiondos;
use sabiondos;
select * from partida;
select * from preguntaUsuario;
select * from usuarios where id = 7;

DELETE FROM preguntaUsuario p WHERE p.idUsuario = 7;
DELETE FROM partida p WHERE p.idUsuario = 7;

update usuarios set puntajeTotal = 0 where id = 7;
update usuarios set partidasJugadas = 0 where id = 7;
update usuarios set preguntasAcertadasTotales = 0 where id = 7;
update usuarios set preguntasRespondidas = 0 where id = 7;

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
                          puntajeMasAlto int,
                          puntajeTotal int not null,
                          partidasJugadas int not null,
                          nivel enum('bajo','medio','alto') not null,
                          preguntasAcertadasTotales int not null,
                          preguntasRespondidas int not null,
                          puntajeRanking int not null
);

ALTER TABLE usuarios MODIFY COLUMN ranking INT;
ALTER TABLE usuarios ADD COLUMN  puntajeRanking int not null;

#ranking --> ahora es --> puntajeRanking --> calculado de PuntajeTotal/PartidasJugadas   --> HECHO
#ranking --> posicion del usuario en la lista ordenda por puntaje ranking   --> HECHO

ALTER TABLE partida MODIFY COLUMN duracion time not null;
ALTER TABLE partida drop COLUMN tiempo;

ALTER TABLE usuarios ADD COLUMN puntajeTotal int not null;
ALTER TABLE usuarios ADD COLUMN  partidasJugadas int not null;
# obtenemos el ranking   --> HECHO

ALTER TABLE usuarios ADD COLUMN  nivel enum('bajo','medio','alto') not null;
ALTER TABLE usuarios ADD COLUMN  preguntasAcertadasTotales int not null;
ALTER TABLE usuarios ADD COLUMN  preguntasRespondidas int not null;
#obtemos el nivel del usuario   --> HECHO
# mover donde se calcula el nivel, deberia estar en el lobby tambien  --> HECHO
# que se pueda recargar tranquilamente   --> HECHO

#QUEDA POR HACER:

# segun nivel de usuario --> dificultad de pregunta
# NIVEL DEL USUARIO  --> preguntas acertadas SOBRE preguntas respondidas   --> HECHO

# LA DIFICULTAD DE LAS PREGUNTAS
# calcular ratio de hacierto --> cantidad de veces que se respondio bien - SOBRE - cantidad de veces entregadas
# ratio de hacierto -->  > 70%  --> facil
# ratio de hacierto -->  < 30%  --> dificil

#Si la pregunta se responde bien más del 70% de las veces, es fácil. Si se responde menos del 30% es dificil.




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
INSERT INTO usuarios (nombre_completo, fecha_nacimiento, sexo, contrasenia, token_validacion, mail, pais, ciudad, foto_perfil, nombre_usuario, cuenta_activa, ranking, puntajeMasAlto, puntajeTotal, partidasJugadas, nivel, preguntasAcertadasTotales, preguntasRespondidas, puntajeRanking)
VALUES
    ('Juan Pérez', '1990-05-15', 'Masculino', 'contraseña123', 'token123', 'juan.perez@example.com', 'Argentina', 'Buenos Aires', 'juan.jpg', 'juanperez', TRUE, 0, 5000, 15000, 100, 'alto', 300, 350, 150),
    ('Ana García', '1985-08-20', 'Femenino', 'contraseña456', 'token456', 'ana.garcia@example.com', 'España', 'Madrid', 'ana.jpg', 'anagarcia', TRUE, 0, 4500, 14000, 90, 'alto', 290, 320, 156),
    ('Carlos López', '1992-12-30', 'Prefiero no cargarlo', 'contraseña789', 'token789', 'carlos.lopez@example.com', 'México', 'Ciudad de México', 'carlos.jpg', 'carloslopez', TRUE, 0, 4000, 13000, 80, 'medio', 250, 300, 163);

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

INSERT INTO pregunta (id_categoria, texto, nivel_dificultad, valor, tiempo_respuesta) VALUES
    (1, '¿Quién fue el primer emperador de Roma?', 'Medio', 20, 30), #ID 1
    (2, '¿Cuántos jugadores conforman un equipo de fútbol?', 'Fácil', 10, 20), #ID 2
    (3, '¿Cuál es la capital de Australia?', 'Medio', 20, 30), #ID 3
    (4, '¿Quién dirigió la película "Titanic"?', 'Fácil', 10, 20), #ID 4
    (5, '¿Cuál es el título de la pintura más famosa de Vincent van Gogh?', 'Difícil', 30, 45), #ID 5
    (1, '¿En qué año cayó el Muro de Berlín?', 'Medio', 20, 30), #ID 6
    (2, '¿En qué deporte se utiliza un bate y una pelota pequeña?', 'Fácil', 10, 20), #ID 7
    (3, '¿Cuál es el desierto más grande del mundo?', 'Difícil', 30, 45), #ID 8
    (4, '¿Qué banda británica lanzó el álbum "Abbey Road"?', 'Medio', 20, 30), #ID 9
    (5, '¿Quién compuso la "Novena Sinfonía"?', 'Difícil', 30, 45); #ID 10




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

INSERT INTO respuesta (texto, pregunta_id, correcta) VALUES
                                                         -- Respuestas para la pregunta 6
                                                         ('Augusto', 6, true),
                                                         ('Nerón', 6, false),
                                                         ('Julio César', 6, false),
                                                         ('Calígula', 6, false),
                                                         -- Respuestas para la pregunta 7
                                                         ('11 jugadores', 7, true),
                                                         ('10 jugadores', 7, false),
                                                         ('12 jugadores', 7, false),
                                                         ('9 jugadores', 7, false),
                                                         -- Respuestas para la pregunta 8
                                                         ('Canberra', 8, true),
                                                         ('Sídney', 8, false),
                                                         ('Melbourne', 8, false),
                                                         ('Brisbane', 8, false),
                                                         -- Respuestas para la pregunta 9
                                                         ('James Cameron', 9, true),
                                                         ('Steven Spielberg', 9, false),
                                                         ('Martin Scorsese', 9, false),
                                                         ('Christopher Nolan', 9, false),
                                                         -- Respuestas para la pregunta 10
                                                         ('La noche estrellada', 10, true),
                                                         ('Los girasoles', 10, false),
                                                         ('La habitación', 10, false),
                                                         ('El café de noche', 10, false),
                                                         -- Respuestas para la pregunta 11
                                                         ('1989', 11, true),
                                                         ('1990', 11, false),
                                                         ('1988', 11, false),
                                                         ('1991', 11, false),
                                                         -- Respuestas para la pregunta 12
                                                         ('Béisbol', 12, true),
                                                         ('Críquet', 12, false),
                                                         ('Hockey', 12, false),
                                                         ('Tenis', 12, false),
                                                         -- Respuestas para la pregunta 13
                                                         ('Desierto de Sahara', 13, true),
                                                         ('Desierto de Gobi', 13, false),
                                                         ('Desierto de Kalahari', 13, false),
                                                         ('Desierto de Atacama', 13, false),
                                                         -- Respuestas para la pregunta 14
                                                         ('The Beatles', 14, true),
                                                         ('The Rolling Stones', 14, false),
                                                         ('Pink Floyd', 14, false),
                                                         ('Led Zeppelin', 14, false),
                                                         -- Respuestas para la pregunta 15
                                                         ('Ludwig van Beethoven', 15, true),
                                                         ('Wolfgang Amadeus Mozart', 15, false),
                                                         ('Johann Sebastian Bach', 15, false),
                                                         ('Franz Schubert', 15, false);


create table partida (
                         id int auto_increment primary key,
                         idUsuario int not null,
                         puntaje int not null,
                         duracion time not null,
                         preguntasAcertadas int,
                         activa boolean not null,

                         foreign key (idUsuario) references usuarios(id)
);


create table preguntaPartida (
                                 id int auto_increment primary key,
                                 idPregunta int not null,
                                 idPartida int not null,
                                 foreign key(idPregunta) references pregunta(id),
                                 foreign key(idPartida) references partida(id)
);
# debemos obtener la cantidad de preguntas que ese usuario respondio

create table preguntaUsuario (
                                 id int auto_increment primary key,
                                 idPregunta int not null,
                                 idUsuario int not null,
                                 foreign key(idPregunta) references pregunta(id),
                                 foreign key(idUsuario) references usuarios(id)
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