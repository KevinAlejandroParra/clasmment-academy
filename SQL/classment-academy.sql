DROP DATABASE IF EXISTS `classment-academy`;
CREATE DATABASE `classment-academy`;
USE `classment-academy`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuarios` (
  `usuario_documento` DECIMAL(10, 0) NOT NULL PRIMARY KEY,
  `usuario_tipo_documento` ENUM('CC', 'CE', 'TI', 'PPN', 'NIT', 'SSN', 'EIN') NOT NULL,
  `usuario_nombre` VARCHAR(100) NOT NULL,
  `usuario_apellido` VARCHAR(100) NOT NULL,
  `usuario_correo` VARCHAR(80) NOT NULL UNIQUE,
  `usuario_telefono` DECIMAL(10, 0),
  `usuario_direccion` TEXT,
  `usuario_password` TEXT, 
  `usuario_nacimiento` DATE NOT NULL,
  `usuario_imagen_url` VARCHAR(255) NOT NULL DEFAULT '/IMG/usuarios/nf.jpg',
  `usuario_fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `usuario_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rol_id` INT NOT NULL DEFAULT 1 -- LLAVE FORANEA
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rol_id` int(12) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `rol_nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `roles` (`rol_id`, `rol_nombre`) VALUES
(1, 'alumno'),
(2, 'coordinador'),
(3, 'administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escuelas`
--

CREATE TABLE `escuelas` (
  `escuela_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `escuela_nombre` VARCHAR(50) NOT NULL,
  `escuela_descripcion` VARCHAR(255) NOT NULL DEFAULT 'Escuela nueva.',
  `escuela_numero` DECIMAL(10, 0) NOT NULL,
  `escuela_ubicacion` TEXT NOT NULL,
  `escuela_imagen_url` VARCHAR(255) NOT NULL DEFAULT '/IMG/escuelas/nf.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `escuelas` (`escuela_id`, `escuela_nombre`, `escuela_descripcion`, `escuela_numero`, `escuela_ubicacion`, `escuela_imagen_url`) VALUES
(1, 'GO FIT', 'Asesoria nutricional y entrenadores certificados a tu disposición.', 1, 'Calle bosa centro', '/IMG/escuelas/nf.jpg'),
(2, 'Taekwondo free', 'Cursos de artes marciales para todas las edades.', 2, 'Avenida Siempre Viva 456', '/IMG/escuelas/nf.jpg'),
(3, 'Centro de Marketing', 'Cursos de marketing y negocios.', 3, 'Boulevard 789', '/IMG/escuelas/nf.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `curso_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `curso_nombre` VARCHAR(100) NOT NULL,
  `curso_descripcion` VARCHAR(255) NOT NULL DEFAULT 'Curso nuevo.',
  `curso_precio` DECIMAL(10, 0) NOT NULL,
  `curso_profesor` VARCHAR(200) NOT NULL,
  `curso_imagen_url` VARCHAR(255) NOT NULL DEFAULT '/IMG/cursos/nf.jpg',
  `escuela_id` INT NOT NULL -- LLAVE FORANEA
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cursos` (`curso_id`, `curso_nombre`, `curso_descripcion`, `curso_precio`, `curso_profesor`, `curso_imagen_url`, `escuela_id`) VALUES
(1, 'Asesoría nutricional', 'Asesoría nutricional con enfoque en hábitos saludables y dietas personalizadas.', 50000, 'Juan Pérez', '/IMG/cursos/1.jpg', 1),
(2, 'Taekwondo', 'Curso de taekwondo para aprender técnicas de defensa y disciplina.', 60000, 'Maria López', '/IMG/cursos/2.jpg', 2),
(3, 'Master Class', 'Clase magistral sobre temas avanzados en el campo seleccionado.', 70000, 'Alejandro Cifuentes', '/IMG/cursos/3.jpg', 3),
(4, 'Entrenamiento Funcional', 'Curso intensivo de entrenamiento funcional para mejorar la condición física.', 80000, 'Carlos Martínez', '/IMG/cursos/nf.jpg', 1),
(5, 'Defensa Personal', 'Técnicas de defensa personal para todas las edades y niveles.', 70000, 'Lucía Fernández', '/IMG/cursos/nf.jpg', 2),
(6, 'Marketing Digital', 'Estrategias avanzadas de marketing digital para aumentar la visibilidad en línea.', 90000, 'Sofía Ramírez', '/IMG/cursos/nf.jpg', 3),
(7, 'Yoga para principiantes', 'Curso de yoga diseñado para personas sin experiencia previa.', 50000, 'Ana García', '/IMG/cursos/nf.jpg', 1),
(8, 'Fotografía Profesional', 'Aprende técnicas profesionales para capturar imágenes impactantes.', 100000, 'Miguel Torres', '/IMG/cursos/nf.jpg', 3),
(9, 'Pilates', 'Mejora tu flexibilidad y fuerza muscular con ejercicios de pilates.', 60000, 'Laura González', '/IMG/cursos/nf.jpg', 1),
(10, 'Boxeo', 'Entrenamiento de boxeo que incluye técnicas de golpeo y defensa.', 65000, 'Diego Hernández', '/IMG/cursos/nf.jpg', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matriculas`
--

CREATE TABLE `matriculas` (
  `matricula_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `matricula_fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `matricula_estado` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `matricula_plan` ENUM('mensual', 'anual', 'trimestral', 'semestral') NOT NULL,
  `curso_id` INT NOT NULL, -- LLAVE FORANEA
  `usuario_id` INT NOT NULL -- LAVE FORANEA
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturacion_pagos`
--

CREATE TABLE `ordenes` (
  `orden_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `usuario_id` INT NOT NULL, -- LLAVE FORANEA
  `orden_fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_compras`
--

CREATE TABLE `detalles_compras` (
  `compra_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `orden_id` INT NOT NULL, -- LLAVE FORANEA
  `pago_id` INT NOT NULL, -- LAVE FORANEA
  `compra_valor` DECIMAL(10, 0) NOT NULL,
  `comprador_nombres` VARCHAR(120) NOT NULL,
  `comprador_correo` VARCHAR(80) NOT NULL,
  `comprador_numero` DECIMAL(10, 0) NOT NULL,
  `comprador_documento` ENUM('CC', 'CE', 'TI', 'PPN', 'NIT', 'SSN', 'EIN') NOT NULL,
  `comprador_numero_documento` DECIMAL(10, 0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pagos`
--

CREATE TABLE `metodos_pagos` (
  `pago_id` INT NOT NULL PRIMARY KEY,
  `pago_nombre` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `metodos_pagos` (`pago_id`, `pago_nombre`) VALUES
(2, 'CREDIT_CARD'),
(4, 'PSE'),
(5, 'ACH'),
(6, 'DEBIT_CARD'),
(7, 'CASH'),
(8, 'REFERENCED'),
(10, 'BANK_REFERENCED'),
(14, 'SPEI');
