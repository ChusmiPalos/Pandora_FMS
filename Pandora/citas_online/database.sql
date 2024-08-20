-- Solo "creamos" la base, en la primera ejecución, la linea siguiente por lo tanto, habitualmente estará comentada, si ya hemos ejecutado esto con anterioridad, y ya existe la base "clinica"
-- CREATE DATABASE clinica;
-- Seleccionamos la Base de datos de esta aplicación
USE clinica;

-- Eliminar tablas si existen
DROP TABLE IF EXISTS citas;
DROP TABLE IF EXISTS pacientes;
DROP TABLE IF EXISTS horarios;

CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    dni VARCHAR(20) NOT NULL UNIQUE,
    telefono VARCHAR(15),
    email VARCHAR(100) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    tipo_cita ENUM('Primera Consulta', 'Revisión') NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    UNIQUE (fecha, hora)
);

CREATE TABLE horarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    disponible BOOLEAN DEFAULT TRUE,
    UNIQUE (fecha, hora)
);

-- Inserción de horarios (por ejemplo, del próximo mes)
-- Solo necesitamos crear el prodecimiento InsertarHorarios() la primera vez que se ejecuta
/*DELIMITER $$

CREATE PROCEDURE InsertarHorarios()
BEGIN
    DECLARE fecha DATE;
    DECLARE fin DATE;
    SET fecha = CURDATE();
    SET fin = DATE_ADD(fecha, INTERVAL 30 DAY);

    WHILE fecha <= fin DO
        INSERT INTO horarios (fecha, hora) VALUES 
            (fecha, '10:00:00'),
            (fecha, '11:00:00'),
            (fecha, '12:00:00'),
            (fecha, '13:00:00'),
            (fecha, '14:00:00'),
            (fecha, '15:00:00'),
            (fecha, '16:00:00'),
            (fecha, '17:00:00'),
            (fecha, '18:00:00'),
            (fecha, '19:00:00'),
            (fecha, '20:00:00'),
            (fecha, '21:00:00');
        SET fecha = DATE_ADD(fecha, INTERVAL 1 DAY);
    END WHILE;
END $$

DELIMITER ;
*/

CALL InsertarHorarios();