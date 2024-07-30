-- Se elimina la base de datos si existe y se crea una nueva
DROP DATABASE IF EXISTS `classment-academy`;
CREATE DATABASE `classment-academy`;
USE `classment-academy`;

-- Estructura de la tabla `roles`
CREATE TABLE `roles` (
  `rol_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `rol_nombre` VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de la tabla `escuelas`
CREATE TABLE `escuelas` (
  `escuela_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `escuela_nombre` VARCHAR(100) NOT NULL,
  `escuela_descripcion` TEXT,
  `escuela_telefono` VARCHAR(20) NOT NULL,
  `escuela_direccion` TEXT NOT NULL,
  `escuela_imagen_url` VARCHAR(255) DEFAULT '/IMG/escuelas/nf.jpg',
  `escuela_fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `escuela_estado` ENUM('activo', 'inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de la tabla `usuarios`
CREATE TABLE `usuarios` (
  `usuario_documento` VARCHAR(20) NOT NULL PRIMARY KEY,
  `usuario_tipo_documento` ENUM('CC', 'CE', 'TI', 'PPN', 'NIT', 'SSN', 'EIN') NOT NULL,
  `usuario_nombre` VARCHAR(100) NOT NULL,
  `usuario_apellido` VARCHAR(100) NOT NULL,
  `usuario_correo` VARCHAR(100) NOT NULL UNIQUE,
  `usuario_password` VARCHAR(255) NOT NULL,
  `usuario_telefono` VARCHAR(20),
  `usuario_direccion` TEXT,
  `usuario_nacimiento` DATE NOT NULL,
  `usuario_imagen_url` VARCHAR(255) DEFAULT '/IMG/usuarios/nf.jpg',
  `usuario_fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `usuario_ultima_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_estado` ENUM('activo', 'inactivo') DEFAULT 'activo',
  `rol_id` INT NOT NULL,
  `escuela_id` INT,
  FOREIGN KEY (`rol_id`) REFERENCES `roles`(`rol_id`),
  FOREIGN KEY (`escuela_id`) REFERENCES `escuelas`(`escuela_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Se agrega un índice para mejorar el rendimiento de las búsquedas por correo electrónico
CREATE INDEX idx_usuario_correo ON `usuarios` (`usuario_correo`);

-- Estructura de la tabla `cursos`
CREATE TABLE `cursos` (
  `curso_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `curso_nombre` VARCHAR(100) NOT NULL,
  `curso_descripcion` TEXT,
  `curso_precio` DECIMAL(10, 2) NOT NULL,
  `curso_cupos` INT NOT NULL,
  `curso_capacidad_maxima` INT NOT NULL,
  `curso_edad_minima` INT NOT NULL DEFAULT 0,
  `curso_imagen_url` VARCHAR(255) DEFAULT '/IMG/cursos/nf.jpg',
  `curso_fecha_inicio` DATE NOT NULL,
  `curso_fecha_fin` DATE NOT NULL,
  `curso_estado` ENUM('activo', 'inactivo', 'completo') DEFAULT 'activo',
  `escuela_id` INT NOT NULL,
  `curso_direccion` TEXT NOT NULL,
  FOREIGN KEY (`escuela_id`) REFERENCES `escuelas`(`escuela_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de la tabla `horarios`
CREATE TABLE `horarios` (
  `horario_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `curso_id` INT NOT NULL,
  `horario_dia_semana` ENUM('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo') NOT NULL,
  `horario_hora_inicio` TIME NOT NULL,
  `horario_hora_fin` TIME NOT NULL,
  FOREIGN KEY (`curso_id`) REFERENCES `cursos`(`curso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de la tabla `profesores_cursos`
CREATE TABLE `profesores_cursos` (
  `profesor_curso_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `usuario_documento` VARCHAR(20) NOT NULL,
  `curso_id` INT NOT NULL,
  FOREIGN KEY (`usuario_documento`) REFERENCES `usuarios`(`usuario_documento`),
  FOREIGN KEY (`curso_id`) REFERENCES `cursos`(`curso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de la tabla `matriculas`
CREATE TABLE `matriculas` (
  `matricula_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `matricula_fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `matricula_fecha_fin` DATE NOT NULL,
  `matricula_estado` ENUM('activo', 'inactivo', 'completado') DEFAULT 'activo',
  `matricula_plan` ENUM('mensual', 'trimestral', 'semestral', 'anual') NOT NULL,
  `curso_id` INT NOT NULL,
  `usuario_documento` VARCHAR(20) NOT NULL,
  FOREIGN KEY (`curso_id`) REFERENCES `cursos`(`curso_id`),
  FOREIGN KEY (`usuario_documento`) REFERENCES `usuarios`(`usuario_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de la tabla `asistencias`
CREATE TABLE `asistencias` (
  `asistencia_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `matricula_id` INT NOT NULL,
  `asistencia_fecha` DATE NOT NULL,
  `asistencia_estado` ENUM('presente', 'ausente', 'justificado') NOT NULL,
  FOREIGN KEY (`matricula_id`) REFERENCES `matriculas`(`matricula_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de la tabla `metodos_pagos`
CREATE TABLE `metodos_pagos` (
  `metodo_pago_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `metodo_pago_nombre` VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de la tabla `ordenes_pagos`
CREATE TABLE `ordenes_pagos` (
  `orden_pago_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `usuario_documento` VARCHAR(20) NOT NULL,
  `curso_id` INT NOT NULL,
  `metodo_pago_id` INT NOT NULL,
  `orden_pago_monto` DECIMAL(10, 2) NOT NULL,
  `orden_pago_fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `orden_pago_estado` ENUM('pendiente', 'pagada', 'cancelada') DEFAULT 'pendiente',
  `orden_pago_fecha_pago` TIMESTAMP NULL,
  `orden_pago_estado_pago` ENUM('pendiente', 'completado', 'fallido') DEFAULT 'pendiente',
  `orden_pago_numero_referencia` VARCHAR(50),
  FOREIGN KEY (`usuario_documento`) REFERENCES `usuarios`(`usuario_documento`),
  FOREIGN KEY (`curso_id`) REFERENCES `cursos`(`curso_id`),
  FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodos_pagos`(`metodo_pago_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de la tabla `facturas`
CREATE TABLE `facturas` (
  `factura_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `orden_pago_id` INT NOT NULL,
  `factura_fecha_emision` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `factura_total` DECIMAL(10, 2) NOT NULL,
  `factura_estado` ENUM('emitida', 'pagada', 'anulada') DEFAULT 'emitida',
  FOREIGN KEY (`orden_pago_id`) REFERENCES `ordenes_pagos`(`orden_pago_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de la tabla `mensajes`
CREATE TABLE `mensajes` (
  `mensaje_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `remitente_documento` VARCHAR(20) NOT NULL,
  `destinatario_documento` VARCHAR(20) NOT NULL,
  `mensaje_asunto` VARCHAR(255) NOT NULL,
  `mensaje_contenido` TEXT NOT NULL,
  `mensaje_fecha_envio` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `mensaje_leido` BOOLEAN DEFAULT FALSE,
  `mensaje_thread_id` INT,
  FOREIGN KEY (`remitente_documento`) REFERENCES `usuarios`(`usuario_documento`),
  FOREIGN KEY (`destinatario_documento`) REFERENCES `usuarios`(`usuario_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Se insertan roles iniciales
INSERT INTO `roles` (`rol_nombre`) VALUES
('alumno'),
('profesor'),
('coordinador'),
('administrador');

-- Se insertan métodos de pago iniciales
INSERT INTO `metodos_pagos` (`metodo_pago_nombre`) VALUES
('Tarjeta de Crédito'),
('PSE'),
('Transferencia Bancaria'),
('Efectivo');

-- Se crean índices adicionales para mejorar el rendimiento
CREATE INDEX idx_curso_estado ON `cursos` (`curso_estado`);
CREATE INDEX idx_matricula_estado ON `matriculas` (`matricula_estado`);
CREATE INDEX idx_orden_pago_estado ON `ordenes_pagos` (`orden_pago_estado`, `orden_pago_estado_pago`);
CREATE INDEX idx_mensaje_destinatario ON `mensajes` (`destinatario_documento`, `mensaje_leido`);


-- Datos de prueba para la tabla `escuelas`
INSERT INTO `escuelas` (`escuela_nombre`, `escuela_descripcion`, `escuela_telefono`, `escuela_direccion`) VALUES
('Escuela de Artes', 'Formación en artes plásticas y visuales', '1234567890', 'Calle 123, Ciudad A'),
('Academia de Música', 'Enseñanza de instrumentos y teoría musical', '9876543210', 'Avenida 456, Ciudad B'),
('Centro de Idiomas', 'Cursos de idiomas para todos los niveles', '5555555555', 'Plaza Principal, Ciudad C');

-- Datos de prueba para la tabla `usuarios`
INSERT INTO `usuarios` (`usuario_documento`, `usuario_tipo_documento`, `usuario_nombre`, `usuario_apellido`, `usuario_correo`, `usuario_password`, `usuario_telefono`, `usuario_direccion`, `usuario_nacimiento`, `rol_id`, `escuela_id`) VALUES
('1001001001', 'CC', 'Juan', 'Pérez', 'juan.perez@email.com', 'password123', '3001234567', 'Calle 789, Ciudad A', '1990-05-15', 1, 1),
('1002002002', 'CC', 'María', 'González', 'maria.gonzalez@email.com', 'password456', '3009876543', 'Avenida 012, Ciudad B', '1985-10-20', 2, 2),
('1003003003', 'CC', 'Carlos', 'Rodríguez', 'carlos.rodriguez@email.com', 'password789', '3005555555', 'Carrera 345, Ciudad C', '1988-03-25', 3, 3),
('1004004004', 'CC', 'Ana', 'Martínez', 'ana.martinez@email.com', 'passwordabc', '3007777777', 'Diagonal 678, Ciudad A', '1992-12-10', 4, 1);

-- Datos de prueba para la tabla `cursos`
INSERT INTO `cursos` (`curso_nombre`, `curso_descripcion`, `curso_precio`, `curso_cupos`, `curso_capacidad_maxima`, `curso_edad_minima`, `curso_fecha_inicio`, `curso_fecha_fin`, `escuela_id`, `curso_direccion`) VALUES
('Pintura al Óleo', 'Curso básico de pintura al óleo', 500000, 15, 20, 16, '2024-08-01', '2024-12-15', 1, 'Salón 101, Escuela de Artes'),
('Piano Avanzado', 'Clases avanzadas de piano', 750000, 10, 12, 18, '2024-09-01', '2025-02-28', 2, 'Sala de Música, Academia de Música'),
('Inglés Intermedio', 'Curso de inglés nivel B1-B2', 600000, 20, 25, 14, '2024-07-15', '2024-12-20', 3, 'Aula 203, Centro de Idiomas');

-- Datos de prueba para la tabla `horarios`
INSERT INTO `horarios` (`curso_id`, `horario_dia_semana`, `horario_hora_inicio`, `horario_hora_fin`) VALUES
(1, 'Lunes', '14:00:00', '16:00:00'),
(1, 'Miércoles', '14:00:00', '16:00:00'),
(2, 'Martes', '18:00:00', '20:00:00'),
(2, 'Jueves', '18:00:00', '20:00:00'),
(3, 'Lunes', '10:00:00', '12:00:00'),
(3, 'Miércoles', '10:00:00', '12:00:00'),
(3, 'Viernes', '10:00:00', '12:00:00');

-- Datos de prueba para la tabla `profesores_cursos`
INSERT INTO `profesores_cursos` (`usuario_documento`, `curso_id`) VALUES
('1002002002', 1),
('1002002002', 2),
('1003003003', 3);

-- Datos de prueba para la tabla `matriculas`
INSERT INTO `matriculas` (`matricula_fecha`, `matricula_fecha_fin`, `matricula_estado`, `matricula_plan`, `curso_id`, `usuario_documento`) VALUES
(CURRENT_TIMESTAMP, '2024-12-15', 'activo', 'semestral', 1, '1001001001'),
(CURRENT_TIMESTAMP, '2025-02-28', 'activo', 'anual', 2, '1001001001'),
(CURRENT_TIMESTAMP, '2024-12-20', 'activo', 'semestral', 3, '1004004004');

-- Datos de prueba para la tabla `asistencias`
INSERT INTO `asistencias` (`matricula_id`, `asistencia_fecha`, `asistencia_estado`) VALUES
(1, '2024-08-01', 'presente'),
(1, '2024-08-06', 'presente'),
(1, '2024-08-08', 'ausente'),
(2, '2024-09-01', 'presente'),
(2, '2024-09-03', 'presente'),
(3, '2024-07-15', 'presente'),
(3, '2024-07-17', 'justificado');

-- Datos de prueba para la tabla `ordenes_pagos`
INSERT INTO `ordenes_pagos` (`usuario_documento`, `curso_id`, `metodo_pago_id`, `orden_pago_monto`, `orden_pago_estado`, `orden_pago_estado_pago`, `orden_pago_numero_referencia`) VALUES
('1001001001', 1, 1, 500000, 'pagada', 'completado', 'REF001'),
('1001001001', 2, 2, 750000, 'pagada', 'completado', 'REF002'),
('1004004004', 3, 3, 600000, 'pendiente', 'pendiente', 'REF003');

-- Datos de prueba para la tabla `facturas`
INSERT INTO `facturas` (`orden_pago_id`, `factura_total`, `factura_estado`) VALUES
(1, 500000, 'pagada'),
(2, 750000, 'pagada'),
(3, 600000, 'emitida');

-- Datos de prueba para la tabla `mensajes`
INSERT INTO `mensajes` (`remitente_documento`, `destinatario_documento`, `mensaje_asunto`, `mensaje_contenido`, `mensaje_leido`, `mensaje_thread_id`) VALUES
('1001001001', '1002002002', 'Consulta sobre el curso', '¿Podría darme más información sobre los materiales necesarios para el curso de Pintura al Óleo?', FALSE, 1),
('1002002002', '1001001001', 'RE: Consulta sobre el curso', 'Claro, con gusto. Para el curso necesitarás lo siguiente: [lista de materiales]', FALSE, 1),
('1004004004', '1003003003', 'Cambio de horario', '¿Es posible cambiar mi horario del curso de Inglés Intermedio?', FALSE, 2),
('1003003003', '1004004004', 'RE: Cambio de horario', 'Podemos discutir las opciones disponibles. ¿Qué horario te convendría más?', FALSE, 2);
