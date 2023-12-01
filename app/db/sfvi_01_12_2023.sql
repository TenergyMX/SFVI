-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-12-2023 a las 22:06:32
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sfvi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `description` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`id`, `description`) VALUES
(1, 'prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `type_of_client` int(11) NOT NULL,
  `name` varchar(133) NOT NULL,
  `surnames` varchar(133) NOT NULL,
  `state` varchar(133) NOT NULL,
  `municipality` varchar(133) NOT NULL,
  `email` varchar(20) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `rfc` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`id`, `type_of_client`, `name`, `surnames`, `state`, `municipality`, `email`, `phone`, `rfc`) VALUES
(5, 4, 'Oliver', 'Suarez', 'Chihuahua', 'El Marquez', 'a', '1', 'a'),
(6, 1, 'Barry', 'Castañeda', 'Guerrero', 'Pedro Escobedo', '', '', ''),
(7, 1, 'Carol', 'DE C.V.', 'GUANAJUATO', 'Colon', 'sergio.velazquez71@g', '4421098296', 'GAIA030401'),
(8, 1, 'Ulises', 'Gutierrez Soto', 'Queretaro', 'Colon', 'ulises@gmail.com', '4421098296', 'UL20EF44'),
(9, 1, 'Ana', 'Lian', 'Querétaro', 'Colon', 'ana@gmail.com', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hsp`
--

CREATE TABLE `hsp` (
  `id` int(11) NOT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `promedio` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hsp`
--

INSERT INTO `hsp` (`id`, `estado`, `promedio`) VALUES
(1, 'Aguascalientes', 6.4),
(2, 'Baja California Norte', 6.8),
(3, 'Baja California Sur', 6.8),
(4, 'Campeche', 6.1),
(5, 'Chiapas', 5.6),
(6, 'Chihuahua', 6.6),
(7, 'Ciudad de Mexico', 5.7),
(8, 'Coahuila', 6.4),
(9, 'Colima', 6.1),
(10, 'Durango', 6.4),
(11, 'Guanajuato', 6.2),
(12, 'Guerrero', 6),
(13, 'Hidalgo', 5.5),
(14, 'Jalisco', 6.4),
(15, 'Mexico', 5.7),
(16, 'Michoacan', 5.8),
(17, 'Morelos', 6.3),
(18, 'Nayarit', 6.4),
(19, 'Nuevo Leon', 6.1),
(20, 'Oaxaca', 5.3),
(21, 'Puebla', 5.8),
(22, 'Quer?taro', 6.8),
(23, 'Quintana_Roo', 5.4),
(24, 'San Luis Potosi', 6.4),
(25, 'Sinaloa', 6.6),
(26, 'Sonora', 6.8),
(27, 'Tabasco', 5.2),
(28, 'Tamaulipas', 5.9),
(29, 'Tlaxcala', 5.8),
(30, 'Veracruz', 5.3),
(31, 'Yucatan', 5.8),
(32, 'Zacatecas', 6.5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `folio` varchar(16) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `id_subcategory` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `quotations` varchar(255) NOT NULL,
  `quotations_num` varchar(255) NOT NULL,
  `id_fide` int(11) NOT NULL,
  `tb_project` varchar(255) NOT NULL,
  `charge` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_date` date NOT NULL,
  `status` varchar(133) NOT NULL,
  `percentage` varchar(16) NOT NULL,
  `lat` varchar(133) NOT NULL,
  `lon` varchar(133) NOT NULL,
  `panels` int(10) DEFAULT NULL,
  `module_capacity` int(10) DEFAULT NULL,
  `efficiency` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `project`
--

INSERT INTO `project` (`id`, `folio`, `id_client`, `id_category`, `id_subcategory`, `id_user`, `quotations`, `quotations_num`, `id_fide`, `tb_project`, `charge`, `address`, `start_date`, `end_date`, `status`, `percentage`, `lat`, `lon`, `panels`, `module_capacity`, `efficiency`) VALUES
(1, 'TB001', 5, 1, 1, 23, '1', '1', 1, '1', '1', 'CDMX, Ciudad de Mexico', '2023-11-24 16:02:31', '2023-11-01', '1', '1', '1', '1', 4, 410, 75),
(2, 'TB002', 7, 1, 1, 27, '1', '1', 1, '1', '1', 'ZIHUATANEJO, GUERRERO', '2023-11-24 15:43:31', '2023-11-23', '1', '1', '1', '1', 4, 410, 75);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_contado_stage1`
--

CREATE TABLE `p_contado_stage1` (
  `id` int(11) NOT NULL,
  `id_project` int(11) NOT NULL,
  `recibo_CFE` varchar(255) NOT NULL,
  `cotizacion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_contado_stage2`
--

CREATE TABLE `p_contado_stage2` (
  `id` int(11) NOT NULL,
  `id_project` int(11) NOT NULL,
  `CFSE` varchar(255) NOT NULL,
  `INE` varchar(255) NOT NULL,
  `pago_anticipo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_contado_stage3`
--

CREATE TABLE `p_contado_stage3` (
  `id` int(11) NOT NULL,
  `id_project3` int(11) NOT NULL,
  `planos` varchar(255) NOT NULL,
  `F_registros` varchar(255) NOT NULL,
  `material_equipo` varchar(255) NOT NULL,
  `contenido_armado` varchar(255) NOT NULL,
  `supervision_1` varchar(255) NOT NULL,
  `supervision_2` varchar(255) NOT NULL,
  `supervision_3` varchar(255) NOT NULL,
  `supervision_4` varchar(255) NOT NULL,
  `trabajo_alturas` varchar(255) NOT NULL,
  `EPP` varchar(255) NOT NULL,
  `memoria_calculo` varchar(255) NOT NULL,
  `valid_compras` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_contado_stage4`
--

CREATE TABLE `p_contado_stage4` (
  `id` int(11) NOT NULL,
  `id_project4` int(11) NOT NULL,
  `planos` varchar(255) NOT NULL,
  `F_registros` varchar(255) NOT NULL,
  `material_equipo` varchar(255) NOT NULL,
  `contenido_armado` varchar(255) NOT NULL,
  `supervision_1` varchar(255) NOT NULL,
  `supervision_2` varchar(255) NOT NULL,
  `supervision_3` varchar(255) NOT NULL,
  `supervision_4` varchar(255) NOT NULL,
  `trabajo_alturas` varchar(255) NOT NULL,
  `EPP` varchar(255) NOT NULL,
  `memoria_calculo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_contado_stage5`
--

CREATE TABLE `p_contado_stage5` (
  `id` int(11) NOT NULL,
  `id_project5` int(11) NOT NULL,
  `interconexion` varchar(255) NOT NULL,
  `contraprestacion` varchar(255) NOT NULL,
  `anexo_2` varchar(255) NOT NULL,
  `img_medidor_b` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_contado_stage6`
--

CREATE TABLE `p_contado_stage6` (
  `id` int(11) NOT NULL,
  `id_project6` int(11) NOT NULL,
  `comprobante_pago` varchar(255) NOT NULL,
  `liberacion_proyecto` varchar(255) NOT NULL,
  `pruebas_funcionamiento` varchar(255) NOT NULL,
  `operacion_mante` varchar(255) NOT NULL,
  `garantias` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_fide_stage1`
--

CREATE TABLE `p_fide_stage1` (
  `id` int(11) NOT NULL,
  `id_project7` int(11) NOT NULL,
  `recibo_CFE` varchar(255) NOT NULL,
  `cotizacion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_fide_stage2`
--

CREATE TABLE `p_fide_stage2` (
  `id` int(11) NOT NULL,
  `id_project8` int(11) NOT NULL,
  `CSF_PM` varchar(255) NOT NULL,
  `acta_constitutiva_pm` varchar(255) NOT NULL,
  `poder_notarial_pm` varchar(255) NOT NULL,
  `INE` varchar(255) NOT NULL,
  `buro_de_credito` varchar(255) NOT NULL,
  `recibo_CFE` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_fide_stage3`
--

CREATE TABLE `p_fide_stage3` (
  `id` int(11) NOT NULL,
  `id_project9` int(11) NOT NULL,
  `CSF_solidario` varchar(255) NOT NULL,
  `CSF_repre_legal` varchar(255) NOT NULL,
  `INE_solidario` varchar(255) NOT NULL,
  `recibo_CFE_aval` varchar(255) NOT NULL,
  `com_domi_aval` varchar(255) NOT NULL,
  `com_dom_erl` varchar(255) NOT NULL,
  `com_dom_negocio` varchar(255) NOT NULL,
  `INE_erl_negocio` varchar(255) NOT NULL,
  `predial_negocio` varchar(255) NOT NULL,
  `img_medidor` varchar(255) NOT NULL,
  `img_fachada` varchar(255) NOT NULL,
  `ref_personal_1` varchar(255) NOT NULL,
  `ref_personal_2` varchar(255) NOT NULL,
  `anexo_tecnico` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_fide_stage4`
--

CREATE TABLE `p_fide_stage4` (
  `id` int(11) NOT NULL,
  `id_project10` int(11) NOT NULL,
  `contrato_tripartita` varchar(255) NOT NULL,
  `pagare` varchar(255) NOT NULL,
  `sol_credito` varchar(255) NOT NULL,
  `insentivo_energetico` varchar(255) NOT NULL,
  `card_comp_OS` varchar(255) NOT NULL,
  `card_reconocimiento` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_fide_stage5`
--

CREATE TABLE `p_fide_stage5` (
  `id` int(11) NOT NULL,
  `id_project11` int(11) NOT NULL,
  `planos` varchar(255) NOT NULL,
  `F_registros` varchar(255) NOT NULL,
  `material_equipo` varchar(255) NOT NULL,
  `contenido_armado` varchar(255) NOT NULL,
  `supervision_1` varchar(255) NOT NULL,
  `supervision_2` varchar(255) NOT NULL,
  `supervision_3` varchar(255) NOT NULL,
  `supervision_4` varchar(255) NOT NULL,
  `trabajo_alturas` varchar(255) NOT NULL,
  `EPP` varchar(255) NOT NULL,
  `memoria_calculo` varchar(255) NOT NULL,
  `valid_compras` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_fide_stage6`
--

CREATE TABLE `p_fide_stage6` (
  `id` int(11) NOT NULL,
  `id_project12` int(11) NOT NULL,
  `repor_fotografico` varchar(255) NOT NULL,
  `p_funcionamiento` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_fide_stage7`
--

CREATE TABLE `p_fide_stage7` (
  `id` int(11) NOT NULL,
  `id_project13` int(11) NOT NULL,
  `liberacion_proyecto` varchar(255) NOT NULL,
  `operacion_mante` varchar(255) NOT NULL,
  `garantias` varchar(255) NOT NULL,
  `doc_finales_fide` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `description` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `role`
--

INSERT INTO `role` (`id`, `description`) VALUES
(1, 'prueba'),
(2, 'Administrador'),
(3, 'Seguimiento'),
(4, 'Técnico'),
(5, 'Vendedor'),
(6, 'Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `status_of_visit`
--

CREATE TABLE `status_of_visit` (
  `id` int(11) NOT NULL,
  `description` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `status_of_visit`
--

INSERT INTO `status_of_visit` (`id`, `description`) VALUES
(1, 'prueba_estatus');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcategory`
--

CREATE TABLE `subcategory` (
  `id` int(11) NOT NULL,
  `description` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subcategory`
--

INSERT INTO `subcategory` (`id`, `description`) VALUES
(1, 'prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `type_of_client`
--

CREATE TABLE `type_of_client` (
  `id` int(11) NOT NULL,
  `description` varchar(133) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `type_of_client`
--

INSERT INTO `type_of_client` (`id`, `description`) VALUES
(1, 'Domestico'),
(3, 'Comercial'),
(4, 'Industrial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `type_of_visit`
--

CREATE TABLE `type_of_visit` (
  `id` int(11) NOT NULL,
  `description` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `type_of_visit`
--

INSERT INTO `type_of_visit` (`id`, `description`) VALUES
(1, 'prueba_visita');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `surnames` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `role`, `name`, `surnames`, `password`) VALUES
(18, 'pedro@gmail.com', 1, 'Leonel', 'Medina', 'pedro123'),
(19, 'sandra@gmail.com', 1, 'Sandra', 'Lopez', ''),
(23, 'luis@gmail.com', 3, 'Luis', 'Soto Perez', ''),
(24, 'Ulices.gutierrez@tenergy.com.mx', 2, 'Luis Ulices', 'Gutierrez', ''),
(25, 'sergio.velazquez71@gmail.com', 2, 'TENERGY', '', ''),
(26, 'sonia@gmail.com', 5, 'Sonia', 'Sanchez', ''),
(27, 'sergio.velazquez71@gmail.com', 2, 'TENERGY', 'DE C.V.', ''),
(28, 'maria@gmail.com', 2, 'Maria', 'Galvan', ''),
(29, 'tomas@gmail.com', 2, 'Tomas', 'Carlos', ''),
(30, 'santos@gmail.com', 3, 'Santos', 'Mendoza', ''),
(31, '', 5, 'Camila', 'Sanchez', ''),
(32, '', 2, 'Carlos', 'Montes', ''),
(33, '', 2, 'Tony', 'Almaraz', 'tony1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visit`
--

CREATE TABLE `visit` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `id_status` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_of_client` (`type_of_client`);

--
-- Indices de la tabla `hsp`
--
ALTER TABLE `hsp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `id_subcategory` (`id_subcategory`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_fide` (`id_fide`);

--
-- Indices de la tabla `p_contado_stage1`
--
ALTER TABLE `p_contado_stage1`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project` (`id_project`);

--
-- Indices de la tabla `p_contado_stage2`
--
ALTER TABLE `p_contado_stage2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project` (`id_project`);

--
-- Indices de la tabla `p_contado_stage3`
--
ALTER TABLE `p_contado_stage3`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project3` (`id_project3`);

--
-- Indices de la tabla `p_contado_stage4`
--
ALTER TABLE `p_contado_stage4`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project4` (`id_project4`);

--
-- Indices de la tabla `p_contado_stage5`
--
ALTER TABLE `p_contado_stage5`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project5` (`id_project5`);

--
-- Indices de la tabla `p_contado_stage6`
--
ALTER TABLE `p_contado_stage6`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project6` (`id_project6`);

--
-- Indices de la tabla `p_fide_stage1`
--
ALTER TABLE `p_fide_stage1`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project7` (`id_project7`);

--
-- Indices de la tabla `p_fide_stage2`
--
ALTER TABLE `p_fide_stage2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project8` (`id_project8`);

--
-- Indices de la tabla `p_fide_stage3`
--
ALTER TABLE `p_fide_stage3`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project9` (`id_project9`);

--
-- Indices de la tabla `p_fide_stage4`
--
ALTER TABLE `p_fide_stage4`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project10` (`id_project10`);

--
-- Indices de la tabla `p_fide_stage5`
--
ALTER TABLE `p_fide_stage5`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project11` (`id_project11`);

--
-- Indices de la tabla `p_fide_stage6`
--
ALTER TABLE `p_fide_stage6`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project12` (`id_project12`);

--
-- Indices de la tabla `p_fide_stage7`
--
ALTER TABLE `p_fide_stage7`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_project13` (`id_project13`);

--
-- Indices de la tabla `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `status_of_visit`
--
ALTER TABLE `status_of_visit`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `type_of_client`
--
ALTER TABLE `type_of_client`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `type_of_visit`
--
ALTER TABLE `type_of_visit`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol` (`role`);

--
-- Indices de la tabla `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_type` (`id_type`),
  ADD KEY `id_status` (`id_status`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `hsp`
--
ALTER TABLE `hsp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `p_contado_stage1`
--
ALTER TABLE `p_contado_stage1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_contado_stage2`
--
ALTER TABLE `p_contado_stage2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_contado_stage3`
--
ALTER TABLE `p_contado_stage3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_contado_stage4`
--
ALTER TABLE `p_contado_stage4`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_contado_stage5`
--
ALTER TABLE `p_contado_stage5`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_contado_stage6`
--
ALTER TABLE `p_contado_stage6`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_fide_stage1`
--
ALTER TABLE `p_fide_stage1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_fide_stage2`
--
ALTER TABLE `p_fide_stage2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_fide_stage3`
--
ALTER TABLE `p_fide_stage3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_fide_stage4`
--
ALTER TABLE `p_fide_stage4`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_fide_stage5`
--
ALTER TABLE `p_fide_stage5`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_fide_stage6`
--
ALTER TABLE `p_fide_stage6`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_fide_stage7`
--
ALTER TABLE `p_fide_stage7`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `status_of_visit`
--
ALTER TABLE `status_of_visit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `type_of_client`
--
ALTER TABLE `type_of_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `type_of_visit`
--
ALTER TABLE `type_of_visit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `visit`
--
ALTER TABLE `visit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `tipo_de_cliente` FOREIGN KEY (`type_of_client`) REFERENCES `type_of_client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `id_category` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_cliente` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_subcategory` FOREIGN KEY (`id_subcategory`) REFERENCES `subcategory` (`id`),
  ADD CONSTRAINT `id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `p_contado_stage1`
--
ALTER TABLE `p_contado_stage1`
  ADD CONSTRAINT `id_project` FOREIGN KEY (`id_project`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `p_contado_stage2`
--
ALTER TABLE `p_contado_stage2`
  ADD CONSTRAINT `id_project2` FOREIGN KEY (`id_project`) REFERENCES `project` (`id`);

--
-- Filtros para la tabla `p_contado_stage3`
--
ALTER TABLE `p_contado_stage3`
  ADD CONSTRAINT `id_project3` FOREIGN KEY (`id_project3`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `p_contado_stage4`
--
ALTER TABLE `p_contado_stage4`
  ADD CONSTRAINT `id_project4` FOREIGN KEY (`id_project4`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `p_contado_stage5`
--
ALTER TABLE `p_contado_stage5`
  ADD CONSTRAINT `id_project5` FOREIGN KEY (`id_project5`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `p_contado_stage6`
--
ALTER TABLE `p_contado_stage6`
  ADD CONSTRAINT `id_project6` FOREIGN KEY (`id_project6`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `p_fide_stage1`
--
ALTER TABLE `p_fide_stage1`
  ADD CONSTRAINT `id_project7` FOREIGN KEY (`id_project7`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `p_fide_stage2`
--
ALTER TABLE `p_fide_stage2`
  ADD CONSTRAINT `id_project8` FOREIGN KEY (`id_project8`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `p_fide_stage3`
--
ALTER TABLE `p_fide_stage3`
  ADD CONSTRAINT `id_project9` FOREIGN KEY (`id_project9`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `p_fide_stage4`
--
ALTER TABLE `p_fide_stage4`
  ADD CONSTRAINT `id_project10` FOREIGN KEY (`id_project10`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `p_fide_stage5`
--
ALTER TABLE `p_fide_stage5`
  ADD CONSTRAINT `id_project11` FOREIGN KEY (`id_project11`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `p_fide_stage6`
--
ALTER TABLE `p_fide_stage6`
  ADD CONSTRAINT `id_project12` FOREIGN KEY (`id_project12`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `p_fide_stage7`
--
ALTER TABLE `p_fide_stage7`
  ADD CONSTRAINT `id_project13` FOREIGN KEY (`id_project13`) REFERENCES `project` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `visit`
--
ALTER TABLE `visit`
  ADD CONSTRAINT `estatus_visita` FOREIGN KEY (`id_status`) REFERENCES `status_of_visit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visit_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visita_tipo` FOREIGN KEY (`id_type`) REFERENCES `type_of_visit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
