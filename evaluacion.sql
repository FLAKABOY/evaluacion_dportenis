-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-05-2025 a las 09:57:15
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `evaluacion`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_menu_json` ()   BEGIN
    SELECT 
        CONCAT(
            '[', 
            GROUP_CONCAT(
                CONCAT(
                    '{',
                        '"id_menu":', m.id_menu, ',',
                        '"nombre":"', m.name, '",',
                        '"descripcion":"', m.description, '",',
                        '"status":', m.status, ',',
                        '"submenus":[',
                            IFNULL((
                                SELECT GROUP_CONCAT(
                                    CONCAT(
                                        '{',
                                            '"id_menu":', sm.id_menu, ',',
                                            '"nombre":"', sm.name, '",',
                                            '"descripcion":"', sm.description, '",',
                                            '"status":', sm.status,
                                        '}'
                                    )
                                )
                                FROM menus sm
                                WHERE sm.id_parent = m.id_menu
                            ), ''),
                        ']',
                    '}'
                )
            ),
            ']'
        ) AS menu_json
    FROM menus m
    WHERE m.id_parent = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_menu_items` (IN `ban` VARCHAR(50), IN `filter` TEXT)   BEGIN
    IF ban = 'get_all' THEN
        SELECT 
            CONCAT(
                '[', 
                GROUP_CONCAT(
                    CONCAT(
                        '{',
                            '"id_menu":', m.id_menu, ',',
                            '"name":"', m.name, '",',
                            '"description":"', m.description, '",',
                            '"status":', m.status, ',',
                            '"id_parent":', m.id_parent,
                        '}'
                    )
                ),
                ']'
            ) AS items
        FROM menus m
        WHERE m.status = 1;

    ELSEIF ban = 'get_menu_parents' THEN
        SELECT 
            CONCAT(
                '[', 
                GROUP_CONCAT(
                    CONCAT(
                        '{',
                            '"id_menu":', m.id_menu, ',',
                            '"name":"', m.name, '",',
                            '"description":"', m.description, '",',
                            '"status":', m.status, ',',
                            '"id_parent":', m.id_parent,
                        '}'
                    )
                ),
                ']'
            ) AS items
        FROM menus m
        WHERE m.status = 1
        AND m.id_parent = 0;  -- Solo menús padres

    ELSE
        SELECT '{"error": "Invalid case"}' AS items;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id_menu` int(11) NOT NULL COMMENT 'identificador del menu',
  `id_parent` int(11) NOT NULL COMMENT 'identificador del menu padre',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT 'estatus del menu',
  `name` varchar(100) NOT NULL COMMENT 'nombre del menu',
  `description` text NOT NULL COMMENT 'descripcion del menu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `menus`
--

INSERT INTO `menus` (`id_menu`, `id_parent`, `status`, `name`, `description`) VALUES
(1, 0, 1, 'Catalogos', 'Menu padre de los catalogos'),
(3, 0, 1, 'Areas', 'menu de areas'),
(4, 1, 1, 'paises', 'catalogo de paises'),
(5, 3, 1, 'TI', 'area de TI');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id_menu`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del menu', AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
