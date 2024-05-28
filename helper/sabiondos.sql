CREATE TABLE usuarios (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          nombre_completo VARCHAR(100) NOT NULL,
                          fecha_nacimiento DATE NOT NULL,
                          sexo ENUM('Masculino', 'Femenino', 'Prefiero no cargarlo') NOT NULL,
                          pais VARCHAR(50) NOT NULL,
                          ciudad VARCHAR(50) NOT NULL,
                          mail VARCHAR(255) NOT NULL UNIQUE,
                          contrasenia_hash VARCHAR(255) NOT NULL,
                          nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
                          foto_perfil VARCHAR(255),
                          fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          cuenta_activa BOOLEAN DEFAULT FALSE,
                          token_validacion VARCHAR(255)
);